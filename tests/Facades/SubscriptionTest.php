<?php

declare(strict_types=1);

namespace Imdhemy\Purchases\Tests\Facades;

use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Psr7\Response;
use Imdhemy\AppStore\ClientFactory;
use Imdhemy\AppStore\Exceptions\InvalidReceiptException;
use Imdhemy\AppStore\Receipts\ReceiptResponse;
use Imdhemy\GooglePlay\ClientFactory as GoogleClientFactory;
use Imdhemy\GooglePlay\Subscriptions\SubscriptionPurchase;
use Imdhemy\GooglePlay\ValueObjects\EmptyResponse;
use Imdhemy\Purchases\Contracts\SubscriptionContract;
use Imdhemy\Purchases\Facades\Subscription;
use Imdhemy\Purchases\Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class SubscriptionTest extends TestCase
{
    /**
     * @var string
     */
    private $token;

    /**
     * @var string
     */
    private $itemId;

    protected function setUp(): void
    {
        parent::setUp();
        $this->token = 'hfjblmhmkloppbpihhhifndn.AO-J1OxOKM878RHwS0Hl2Ti3WCvySGw9QTi5WtEUJHO4ppW7ai62vXtruAfOGFWFVdG8Spb3aJRnooesbP_Yfyo0_PXn6LCQ5g';
        $this->itemId = 'week_premium';
    }

    /**
     * @throws GuzzleException|InvalidReceiptException
     *
     * @test
     */
    #[Test]
    public function facade_can_get_a_google_play_subscription()
    {
        $client = GoogleClientFactory::mock(new Response(200, [], '[]'));
        $subscription = Subscription::googlePlay($client)
            ->packageName('com.twigano.fashion')
            ->id($this->itemId)
            ->token($this->token);

        $getResponse = $subscription->get();
        $stdSubscription = $subscription->toStd();

        $this->assertInstanceOf(SubscriptionPurchase::class, $getResponse);
        $this->assertInstanceOf(SubscriptionContract::class, $stdSubscription);
    }

    /**
     * @throws GuzzleException
     *
     * @test
     */
    #[Test]
    public function facade_can_acknowledge_a_google_play_subscription()
    {
        $client = GoogleClientFactory::mock(new Response());

        $this->assertInstanceOf(
            EmptyResponse::class,
            Subscription::googlePlay($client)->packageName('com.twigano.fashion')->id($this->itemId)->token(
                $this->token
            )->acknowledge()
        );
    }

    /**
     * @throws GuzzleException|InvalidReceiptException
     *
     * @test
     */
    #[Test]
    public function it_can_send_verify_receipt_request_to_app_store()
    {
        $receipt = json_decode(file_get_contents($this->fixturesDir('iOS-receipt.json')), true);
        $receiptData = $receipt['transactionReceipt'];
        $password = 'app_store_fake_password';
        $client = ClientFactory::mock(new Response(200, [], json_encode(['status' => 0])));

        $subscription = Subscription::appStore($client)->receiptData($receiptData)->password($password);
        $receiptResponse = $subscription->verifyReceipt();

        $this->assertInstanceOf(ReceiptResponse::class, $receiptResponse);
    }

    /**
     * @throws GuzzleException|InvalidReceiptException
     *
     * @test
     */
    #[Test]
    public function it_handles_the_21007_error_from_the_app_store()
    {
        $receipt = json_decode(file_get_contents($this->fixturesDir('iOS-receipt.json')), true);
        $receiptData = $receipt['transactionReceipt'];
        $password = 'app_store_fake_password';

        $client = ClientFactory::mockQueue([
            new Response(200, [], json_encode(['status' => 21007])),
            new Response(200, [], json_encode(['status' => 0])),
        ]);

        $response = Subscription::appStore($client)
            ->receiptData($receiptData)
            ->password($password)
            ->verifyRenewable($client);

        $this->assertTrue($response->getStatus()->isValid());
    }

    #[Test]
    public function cancel_google_subscription(): void
    {
        $this->expectNotToPerformAssertions();

        $client = GoogleClientFactory::mock(new Response());

        Subscription::googlePlay($client)
            ->packageName('com.twigano.fashion')
            ->id($this->itemId)
            ->token($this->token)
            ->cancel();
    }
}
