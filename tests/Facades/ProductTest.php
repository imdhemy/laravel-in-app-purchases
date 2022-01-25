<?php

namespace Imdhemy\Purchases\Tests\Facades;

use GuzzleHttp\Exception\GuzzleException;
use Huawei\IAP\Response\OrderResponse;
use Imdhemy\AppStore\Receipts\ReceiptResponse;
use Imdhemy\GooglePlay\Products\ProductPurchase;
use Imdhemy\Purchases\Facades\Product;
use Imdhemy\Purchases\Tests\TestCase;

class ProductTest extends TestCase
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
        $this->token = 'pbehplldfhianpgebmleegak.AO-J1Ox7SK22iXuGeWyOVQ-iCokC4UNOqiVwObG4avOfGCovt7GbpA7ih9KdXr4yQTmQUOPQulMksyVmaTq3VR2-VlTss_Pyue6i6aFgBotxvf2oXyTFfww';
        $this->itemId = 'boost_profile';
    }

    /**
     * @test
     * @throws GuzzleException
     */
    public function test_facade_can_get_google_play_product()
    {
        $productPurchase = Product::googlePlay()->id($this->itemId)->token($this->token)->get();
        $this->assertInstanceOf(ProductPurchase::class, $productPurchase);
    }

    /**
     * @test
     */
    public function test_facade_can_acknowledge_google_play_product()
    {
        $this->assertNull(
            Product::googlePlay()->id($this->itemId)->token($this->token)->acknowledge()
        );
    }

    /**
     * @test
     * @throws GuzzleException
     */
    public function test_facade_can_verify_product_receipt()
    {
        $productReceipt = json_decode(file_get_contents(__DIR__.'/../product-receipt.json'), true);
        $receiptData = $productReceipt['transactionReceipt'];
        $password = env('APPSTORE_PASSWORD');

        $this->assertInstanceOf(
            ReceiptResponse::class,
            Product::appStore()->receiptData($receiptData)->password($password)->verifyReceipt()
        );
    }

    /**
     * @test
     */
    public function test_it_can_send_verify_product_request_to_app_gallery()
    {
        $orderResponse = Product::appGallery()->appGalleryValidatePurchase(
            'trial_live_sub_7days',
            '0000017e68a3c8e551fb88d4c6e1d21e49ac88973777eca2b3fdc83ec9b149388daddc3345fdeea0x4359.7.5065'
        );

        $this->assertInstanceOf(OrderResponse::class, $orderResponse);
    }
}
