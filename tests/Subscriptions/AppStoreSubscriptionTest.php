<?php

namespace Imdhemy\Purchases\Tests\Subscriptions;

use Imdhemy\Purchases\Contracts\SubscriptionContract;
use Imdhemy\Purchases\Subscriptions\AppStoreSubscription;
use Imdhemy\Purchases\Tests\TestCase;
use Imdhemy\Purchases\ValueObjects\Time;

class AppStoreSubscriptionTest extends TestCase
{
    /**
     * @var AppStoreSubscription
     */
    private $appStoreSubscription;

    /**
     * @inheritDoc
     */
    public function setUp(): void
    {
        parent::setUp();
        $appStoreResponse = unserialize(file_get_contents(__DIR__ . '/../appstore-verifier-response.ser'));
        $this->appStoreSubscription = new AppStoreSubscription($appStoreResponse->getLatestReceiptInfo()[0]);
    }

    /**
     * @test
     */
    public function test_constructor()
    {
        $this->assertInstanceOf(SubscriptionContract::class, $this->appStoreSubscription);
    }

    /**
     * @test
     */
    public function test_get_expiration_date()
    {
        $this->assertInstanceOf(Time::class, $this->appStoreSubscription->getExpiryTime());
    }

    /**
     * @test
     */
    public function test_get_item_id()
    {
        $this->assertEquals('month_premium', $this->appStoreSubscription->getItemId());
    }
}
