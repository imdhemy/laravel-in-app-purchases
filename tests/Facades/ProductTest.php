<?php

namespace Imdhemy\Purchases\Tests\Facades;

use GuzzleHttp\Exception\GuzzleException;
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
        $productReceipt = json_decode(file_get_contents(__DIR__ . '/../product-receipt.json'), true);
        $receiptData = $productReceipt['transactionReceipt'];
        $password = env('APPSTORE_PASSWORD');

        $this->assertInstanceOf(
            ReceiptResponse::class,
            Product::appStore()->receiptData($receiptData)->password($password)->verifyReceipt()
        );
    }
}
