<?php

declare(strict_types=1);

namespace Imdhemy\Purchases\Tests\Events;

use Imdhemy\Purchases\Contracts\ServerNotificationContract;
use Imdhemy\Purchases\Contracts\SubscriptionContract;
use Imdhemy\Purchases\Events\PurchaseEvent;
use Imdhemy\Purchases\Tests\TestCase;

class PurchaseEventTest extends TestCase
{
    /** @test */
    public function get_server_notification(): void
    {
        $serverNotification = $this->mock(ServerNotificationContract::class);
        $sut = $this->getMockForAbstractClass(PurchaseEvent::class, [$serverNotification]);

        $this->assertSame($serverNotification, $sut->getServerNotification());
    }

    /** @test */
    public function get_subscription(): void
    {
        $subscription = $this->mock(SubscriptionContract::class);
        $serverNotification = $this->mock(ServerNotificationContract::class);
        $serverNotification->shouldReceive('getSubscription')->andReturn($subscription);
        $sut = $this->getMockForAbstractClass(PurchaseEvent::class, [$serverNotification]);

        $this->assertSame($subscription, $sut->getSubscription());
    }

    /** @test */
    public function get_subscription_id(): void
    {
        $subscription = $this->mock(SubscriptionContract::class);
        $subscription->shouldReceive('getItemId')->andReturn('com.example.subscription');
        $serverNotification = $this->mock(ServerNotificationContract::class);
        $serverNotification->shouldReceive('getSubscription')->andReturn($subscription);
        $sut = $this->getMockForAbstractClass(PurchaseEvent::class, [$serverNotification]);

        $this->assertSame('com.example.subscription', $sut->getSubscriptionId());
    }

    /** @test */
    public function get_subscription_identifier(): void
    {
        $subscription = $this->mock(SubscriptionContract::class);
        $subscription->shouldReceive('getUniqueIdentifier')->andReturn('com.example.subscription.123456');
        $serverNotification = $this->mock(ServerNotificationContract::class);
        $serverNotification->shouldReceive('getSubscription')->andReturn($subscription);
        $sut = $this->getMockForAbstractClass(PurchaseEvent::class, [$serverNotification]);

        $this->assertSame('com.example.subscription.123456', $sut->getSubscriptionIdentifier());
    }
}
