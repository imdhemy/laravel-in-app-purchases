<?php

declare(strict_types=1);

namespace Imdhemy\Purchases\Tests\Facades;

use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use Imdhemy\AppStore\Exceptions\InvalidReceiptException;
use Imdhemy\AppStore\Receipts\ReceiptResponse;
use Imdhemy\GooglePlay\ClientFactory;
use Imdhemy\GooglePlay\Products\ProductClient;
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

    /** @test */
    public function facade_can_consume_google_play_product(): void
    {
        $history = [];
        $response = new Response(200, [], '[]');
        $client = ClientFactory::mock($response, $history);

        Product::googlePlay($client)->id($this->itemId)->token($this->token)->consume();

        $this->assertCount(1, $history);
        /** @var Request $request */
        $request = $history[0]['request'];
        $expectedUri = sprintf(ProductClient::URI_CONSUME, 'com.some.thing', $this->itemId, $this->token);
        $this->assertEquals($expectedUri, $request->getUri()->__toString());
    }
}
