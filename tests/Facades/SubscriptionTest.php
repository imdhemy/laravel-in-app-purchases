<?php

namespace Imdhemy\Purchases\Tests\Facades;

use Exception;
use GuzzleHttp\Exception\GuzzleException;
use Huawei\IAP\Response\SubscriptionResponse;
use Imdhemy\AppStore\Exceptions\InvalidReceiptException;
use Imdhemy\AppStore\Receipts\ReceiptResponse;
use Imdhemy\GooglePlay\ClientFactory;
use Imdhemy\GooglePlay\Subscriptions\SubscriptionPurchase;
use Imdhemy\Purchases\Contracts\SubscriptionContract;
use Imdhemy\Purchases\Facades\Subscription;
use Imdhemy\Purchases\Tests\TestCase;

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

    /**
     * @inheritDoc
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->token = 'hfjblmhmkloppbpihhhifndn.AO-J1OxOKM878RHwS0Hl2Ti3WCvySGw9QTi5WtEUJHO4ppW7ai62vXtruAfOGFWFVdG8Spb3aJRnooesbP_Yfyo0_PXn6LCQ5g';
        $this->itemId = 'week_premium';
    }

    /**
     * @test
     * @throws GuzzleException|InvalidReceiptException
     */
    public function test_facade_can_get_a_google_play_subscription()
    {
        $subscription = Subscription::googlePlay()
            ->packageName('com.twigano.fashion')
            ->id($this->itemId)
            ->token($this->token);

        $getResponse = $subscription->get();
        $stdSubscription = $subscription->toStd();

        $this->assertInstanceOf(SubscriptionPurchase::class, $getResponse);
        $this->assertInstanceOf(SubscriptionContract::class, $stdSubscription);
    }

    /**
     * @test
     */
    public function test_facade_can_acknowledge_a_google_play_subscription()
    {
        // TODO: update testing and implementation due the recent update in Google Play Billing Package
        // It's not void anymore
        $this->assertNull(
            Subscription::googlePlay()->packageName('com.twigano.fashion')->id($this->itemId)->token($this->token)->acknowledge()
        );
    }

    /**
     * @test
     * @throws GuzzleException|InvalidReceiptException
     */
    public function test_it_can_send_verify_receipt_request_to_app_store()
    {
        $receipt = json_decode(file_get_contents(__DIR__.'/../iOS-receipt.json'), true);
        $receiptData = $receipt['transactionReceipt'];
        $password = env('APPSTORE_PASSWORD');

        $subscription = Subscription::appStore()->receiptData($receiptData)->password($password);
        $receiptResponse = $subscription->verifyReceipt();
        $stdSubscription = $subscription->toStd();

        $this->assertInstanceOf(ReceiptResponse::class, $receiptResponse);
        $this->assertInstanceOf(SubscriptionContract::class, $stdSubscription);
    }

    /**
     * @test
     * @throws GuzzleException|InvalidReceiptException
     */
    public function test_it_handles_the_21007_error_from_the_app_store()
    {
        $receipt = json_decode(file_get_contents(__DIR__.'/../iOS-receipt.json'), true);
        $receiptData = $receipt['transactionReceipt'];
        $password = env('APPSTORE_PASSWORD');

        $response = Subscription::appStore()->receiptData($receiptData)->password($password)->verifyRenewable();
        $this->assertTrue($response->getStatus()->isValid());
    }

    /**
     * @test
     * @throws Exception
     * @throws GuzzleException
     */
    public function test_custom_client_can_be_set_on_google_play()
    {
        $jsonStream = file_get_contents(__DIR__.'/../../google-app-credentials.json');
        $jsonKey = json_decode($jsonStream, true);
        $client = ClientFactory::createWithJsonKey($jsonKey, [ClientFactory::SCOPE_ANDROID_PUBLISHER]);
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
     * @test
     */
    public function test_it_can_send_verify_subscription_request_to_app_gallery()
    {
        $subscription = Subscription::appGallery()->appGalleryValidateSubscription(
            '1643019045801.766E9D93.0498',
            'trial_live_sub_7days',
            '0000017e68a3c8e551fb88d4c6e1d21e49ac88973777eca2b3fdc83ec9b149388daddc3345fdeea0x4359.7.5065'
        );

        $this->assertInstanceOf(SubscriptionResponse::class, $subscription);
    }
}
