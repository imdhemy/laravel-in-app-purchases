<?php

declare(strict_types=1);

namespace Imdhemy\Purchases\Tests\Facades;

use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Psr7\Response;
use Imdhemy\AppStore\Exceptions\InvalidReceiptException;
use Imdhemy\AppStore\Receipts\ReceiptResponse;
use Imdhemy\GooglePlay\ClientFactory;
use Imdhemy\GooglePlay\Products\ProductPurchase;
use Imdhemy\GooglePlay\ValueObjects\EmptyResponse;
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
     * {@inheritDoc}
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->token = 'pbehplldfhianpgebmleegak.AO-J1Ox7SK22iXuGeWyOVQ-iCokC4UNOqiVwObG4avOfGCovt7GbpA7ih9KdXr4yQTmQUOPQulMksyVmaTq3VR2-VlTss_Pyue6i6aFgBotxvf2oXyTFfww';
        $this->itemId = 'boost_profile';
    }

    /**
     * @test
     *
     * @throws GuzzleException
     */
    public function test_facade_can_get_google_play_product()
    {
        $client = ClientFactory::mock(new Response(200, [], '[]'));
        $productPurchase = Product::googlePlay($client)->id($this->itemId)->token($this->token)->get();
        $this->assertInstanceOf(ProductPurchase::class, $productPurchase);
    }

    /**
     * @test
     *
     * @throws GuzzleException
     */
    public function test_facade_can_acknowledge_google_play_product()
    {
        $client = ClientFactory::mock(new Response(200, [], '[]'));
        $response = Product::googlePlay($client)->id($this->itemId)->token($this->token)->acknowledge();

        $this->assertInstanceOf(EmptyResponse::class, $response);
    }

    /**
     * @test
     *
     * @throws GuzzleException|InvalidReceiptException
     */
    public function test_facade_can_verify_product_receipt()
    {
        $productReceipt = json_decode(file_get_contents($this->fixturesDir('product-receipt.json')), true);
        $receiptData = $productReceipt['transactionReceipt'];
        $password = 'fake_password';

        $status = 0;
        $client = \Imdhemy\AppStore\ClientFactory::mock(new Response(200, [], json_encode(['status' => $status])));

        $receiptResponse = Product::appStore($client)->receiptData($receiptData)->password($password)->verifyReceipt();
        $this->assertInstanceOf(ReceiptResponse::class, $receiptResponse);
        $this->assertEquals($status, $receiptResponse->getStatus()->getValue());
    }
}
