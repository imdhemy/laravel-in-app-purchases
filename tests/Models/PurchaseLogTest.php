<?php

namespace Imdhemy\Purchases\Tests\Models;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Imdhemy\Purchases\Exceptions\CouldNotCreateGoogleClient;
use Imdhemy\Purchases\Exceptions\CouldNotCreateSubscription;
use Imdhemy\Purchases\GooglePlay\Subscriptions\Response;
use Imdhemy\Purchases\GooglePlay\Subscriptions\Subscription;
use Imdhemy\Purchases\Models\PurchaseLog;
use Imdhemy\Purchases\Tests\TestCase;

/**
 * Class PurchaseLogTest
 * @package Imdhemy\Purchases\Tests\Models
 */
class PurchaseLogTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @var PurchaseLog
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
        $token = config(self::SUBSCRIPTION_PURCHASE_TOKEN);
        $this->response = Subscription::check($id, $token)->getResponse();
        $this->purchase = PurchaseLog::fromResponse($this->response);
    }

    /**
     * @test

     */
    public function it_can_be_created_from_response()
    {
        $this->assertInstanceOf(PurchaseLog::class, $this->purchase);
    }

    /**
     * @test
     */
    public function it_can_be_persisted_if_created_from_response()
    {
        $this->purchase->save();

        $this->assertDatabaseHas('purchase_logs', [
            'purchase_token' => $this->response->getPurchaseToken(),
        ]);
    }
}
