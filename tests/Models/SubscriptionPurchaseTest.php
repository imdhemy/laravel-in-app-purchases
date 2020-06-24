<?php

namespace Imdhemy\Purchases\Tests\Models;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Imdhemy\Purchases\Exceptions\CouldNotCreateGoogleClient;
use Imdhemy\Purchases\Exceptions\CouldNotCreateSubscription;
use Imdhemy\Purchases\GooglePlay\Subscriptions\Response;
use Imdhemy\Purchases\GooglePlay\Subscriptions\Subscription;
use Imdhemy\Purchases\Models\SubscriptionPurchase;
use Imdhemy\Purchases\Tests\TestCase;

/**
 * Class SubscriptionPurchaseTest
 * @package Imdhemy\Purchases\Tests\Models
 */
class SubscriptionPurchaseTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @var SubscriptionPurchase
     */
    private $purchase;

    /**
     * @var Response
     */
    private $response;

    /**
     * @throws CouldNotCreateGoogleClient
     * @throws CouldNotCreateSubscription
     */
    public function setUp(): void
    {
        parent::setUp();

        $id = config(self::SUBSCRIPTION_ID);
        $token = config(self::PURCHASE_TOKEN);
        $this->response = Subscription::check($id, $token)->getResponse();
        $this->purchase = SubscriptionPurchase::fromResponse($this->response);
    }

    /**
     * @test

     */
    public function it_can_be_created_from_response()
    {
        $this->assertInstanceOf(SubscriptionPurchase::class, $this->purchase);
    }

    /**
     * @test
     */
    public function it_can_be_persisted_if_created_from_response()
    {
        $this->purchase->save();

        $this->assertDatabaseHas('subscription_purchases', [
            'purchase_token' => $this->response->getPurchaseToken(),
            'expiry_time' => $this->response->getExpiryTimeMillis(),
            'start_time' => $this->response->getStartTimeMillis(),
            'price_amount_micros' => $this->response->getPriceAmountMicros(),
            'price_currency_code' => $this->response->getPriceCurrencyCode(),
        ]);
    }
}
