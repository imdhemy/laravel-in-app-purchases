<?php

namespace Imdhemy\Purchases\Tests\GooglePlay;

use Imdhemy\Purchases\Exceptions\CouldNotCreateGoogleClient;
use Imdhemy\Purchases\Exceptions\CouldNotCreateSubscription;
use Imdhemy\Purchases\GooglePlay\Subscriptions\Response;
use Imdhemy\Purchases\GooglePlay\Subscriptions\Subscription;
use Imdhemy\Purchases\Tests\Models\SubscriptionPurchase;
use Imdhemy\Purchases\Tests\TestCase;

/**
 * Class SubscriptionTest
 * @package Imdhemy\Purchases\Tests\GooglePlay
 */
class SubscriptionTest extends TestCase
{
    /**
     * @test
     * @throws CouldNotCreateSubscription
     * @throws CouldNotCreateGoogleClient
     */
    public function it_can_get_subscription_response()
    {
        $id = config('purchases.subscription_id');
        $token = config('purchases.purchase_token');

        $response = Subscription::check($id, $token)->getResponse();
        $this->assertInstanceOf(Response::class, $response);
    }

    /**
     * @test
     * @throws CouldNotCreateSubscription
     * @throws CouldNotCreateGoogleClient
     */
    public function it_can_create_subscription_purchase()
    {
        $id = config('purchases.subscription_id');
        $token = config('purchases.purchase_token');
        $purchase = Subscription::check($id, $token)->toPurchase();
        $this->assertInstanceOf(SubscriptionPurchase::class, $purchase);
    }
}
