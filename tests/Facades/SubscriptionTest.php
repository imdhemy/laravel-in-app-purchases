<?php

namespace Imdhemy\Purchases\Tests\Facades;

use GuzzleHttp\Exception\GuzzleException;
use Imdhemy\AppStore\Receipts\ReceiptResponse;
use Imdhemy\GooglePlay\Subscriptions\SubscriptionPurchase;
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
        $this->token = 'hhblgkiaeomlogloocbpnkce.AO-J1OzV6UAh0NbPTYq92zJ-P3zhsgc1whL3RiEM1WOlWEUjuypjlIHDRsL8lzAXW7DcSuD2Zr-lDLar9S8Clbsq25bqhf7MHQ';
        $this->itemId = 'month_premium';
    }

    /**
     * @test
     */
    public function test_facade_can_get_a_google_play_subscription()
    {
        $this->assertInstanceOf(
            SubscriptionPurchase::class,
            Subscription::googlePlay()->packageName('com.twigano.fashion')->id($this->itemId)->token($this->token)->get()
        );
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
     * @throws GuzzleException
     */
    public function test_it_can_send_verify_receipt_request_to_app_store()
    {
        $receipt = json_decode(file_get_contents(__DIR__ . '/../iOS-receipt.json'), true);
        $receiptData = $receipt['transactionReceipt'];
        $password = env('APPSTORE_PASSWORD');

        $this->assertInstanceOf(
            ReceiptResponse::class,
            Subscription::appStore()->receiptData($receiptData)->password($password)->verifyReceipt()
        );
    }

    /**
     * @test
     * @throws GuzzleException
     */
    public function test_it_handles_the_21007_error_from_the_app_store()
    {
        $receipt = json_decode(file_get_contents(__DIR__ . '/../iOS-receipt.json'), true);
        $receiptData = $receipt['transactionReceipt'];
        $password = env('APPSTORE_PASSWORD');

        $response = Subscription::appStore()->receiptData($receiptData)->password($password)->verifyRenewable();
        $this->assertTrue($response->getStatus()->isValid());
    }
}
