<?php

namespace Tests\Subscriptions;

use Faker\Factory;
use Imdhemy\AppStore\ValueObjects\LatestReceiptInfo;
use Imdhemy\Purchases\Contracts\SubscriptionContract;
use Imdhemy\Purchases\Subscriptions\AppStoreSubscription;
use Imdhemy\Purchases\ValueObjects\Time;
use Tests\TestCase;

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

        $faker = Factory::create();

        $receipt = LatestReceiptInfo::fromArray([
            'original_transaction_id' => $faker->uuid(),
            'product_id' => 'month_premium',
            'quantity' => 1,
            'transaction_id' => $faker->uuid(),
            'expires_date_ms' => time() * 1000,
        ]);

        $this->appStoreSubscription = new AppStoreSubscription($receipt);
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
