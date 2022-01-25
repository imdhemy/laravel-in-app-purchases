<?php

namespace Imdhemy\Purchases\Tests\Subscriptions;

use Huawei\IAP\Response\SubscriptionResponse;
use Imdhemy\Purchases\Contracts\SubscriptionContract;
use Imdhemy\Purchases\Subscriptions\AppGallerySubscription;
use Imdhemy\Purchases\Tests\TestCase;
use Imdhemy\Purchases\ValueObjects\Time;

class AppGallerySubscriptionTest extends TestCase
{
    /**
     * @var AppGallerySubscription
     */
    private $appGallerySubscription;

    /**
     * @inheritDoc
     */
    public function setUp(): void
    {
        parent::setUp();
        $appGalleryResponse = json_decode(file_get_contents(__DIR__ . '/../appgallery-verify-subscription.json'), true);
        $subscriptionResponse = new SubscriptionResponse($appGalleryResponse);
        $this->appGallerySubscription = new AppGallerySubscription($subscriptionResponse);
    }

    /**
     * @test
     */
    public function test_constructor()
    {
        $this->assertInstanceOf(SubscriptionContract::class, $this->appGallerySubscription);
    }

    /**
     * @test
     */
    public function test_get_expiration_date()
    {
        $this->assertInstanceOf(Time::class, $this->appGallerySubscription->getExpiryTime());
    }

    /**
     * @test
     */
    public function test_get_item_id()
    {
        $this->assertEquals('express_sub_7days', $this->appGallerySubscription->getItemId());
    }
}
