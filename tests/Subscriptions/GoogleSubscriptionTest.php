<?php

namespace Imdhemy\Purchases\Tests\Subscriptions;

use GuzzleHttp\Exception\GuzzleException;
use Imdhemy\GooglePlay\DeveloperNotifications\DeveloperNotification;
use Imdhemy\Purchases\Contracts\SubscriptionContract;
use Imdhemy\Purchases\Facades\Subscription;
use Imdhemy\Purchases\Subscriptions\GoogleSubscription;
use Imdhemy\Purchases\Tests\TestCase;
use Imdhemy\Purchases\ValueObjects\Time;

class GoogleSubscriptionTest extends TestCase
{
    /**
     * @var GoogleSubscription
     */
    private $googleSubscription;

    /**
     * @throws GuzzleException
     */
    public function setUp(): void
    {
        parent::setUp();
        $data = 'eyJ2ZXJzaW9uIjoiMS4wIiwicGFja2FnZU5hbWUiOiJjb20udHdpZ2Fuby5mYXNoaW9uIiwiZXZlbnRUaW1lTWlsbGlzIjoiMTYwNDAwMjg0MjMzMiIsInN1YnNjcmlwdGlvbk5vdGlmaWNhdGlvbiI6eyJ2ZXJzaW9uIjoiMS4wIiwibm90aWZpY2F0aW9uVHlwZSI6MTMsInB1cmNoYXNlVG9rZW4iOiJuYWRpZmJwZWtmZmRjYmNvZGdwa2NrYWMuQU8tSjFPekxnU0ZQSWFESmVKTVF4dnZIYnBMeTlLZ3dYbHVyQjk1UlBINHFZdGYxSmdzV1B3NHV4bmlkYUlmeGJreXVpTDVOZ3ZudVU3TEpvNzIzeHpfVVRhUEZXc0YyZEEiLCJzdWJzY3JpcHRpb25JZCI6Im1vbnRoX3ByZW1pdW0ifX0=';
        $developerNotification = DeveloperNotification::parse($data);
        $subscriptionNotification = $developerNotification->getSubscriptionNotification();
        $packageName = $developerNotification->getPackageName();
        $subscriptionPurchase = Subscription::googlePlay()
            ->packageName($packageName)
            ->token($subscriptionNotification->getPurchaseToken())
            ->id($subscriptionNotification->getSubscriptionId())
            ->get();
        $this->googleSubscription = new GoogleSubscription(
            $subscriptionPurchase,
            $subscriptionNotification->getSubscriptionId(),
            $subscriptionNotification->getPurchaseToken()
        );
    }

    /**
     * @test
     */
    public function test_constructor()
    {
        $this->assertInstanceOf(
            SubscriptionContract::class,
            $this->googleSubscription
        );
    }

    /**
     * @test
     */
    public function test_get_expiry_time()
    {
        $this->assertInstanceOf(
            Time::class,
            $this->googleSubscription->getExpiryTime()
        );
    }

    /**
     * @test
     */
    public function test_get_item_id()
    {
        $this->assertEquals("month_premium", $this->googleSubscription->getItemId());
    }
}
