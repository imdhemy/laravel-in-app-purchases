<?php

declare(strict_types=1);

namespace Imdhemy\Purchases\Tests\Events;

use Imdhemy\Purchases\Contracts\ServerNotificationContract;
use Imdhemy\Purchases\Contracts\SubscriptionContract;
use Imdhemy\Purchases\Events\PurchaseEvent;
use Imdhemy\Purchases\Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class PurchaseEventTest extends TestCase
{
    #[Test]
    public function get_server_notification(): void
    {
        $serverNotification = $this->mock(ServerNotificationContract::class);
        $sut = new class($serverNotification) extends PurchaseEvent {
        };

        $this->assertSame($serverNotification, $sut->getServerNotification());
    }

    #[Test]
    public function get_subscription(): void
    {
        $subscription = $this->mock(SubscriptionContract::class);
        $serverNotification = $this->mock(ServerNotificationContract::class);
        $serverNotification->shouldReceive('getSubscription')->andReturn($subscription);
        $sut = new class($serverNotification) extends PurchaseEvent {
        };

        $this->assertSame($subscription, $sut->getSubscription());
    }

    #[Test]
    public function get_subscription_id(): void
    {
        $subscription = $this->mock(SubscriptionContract::class);
        $subscription->shouldReceive('getItemId')->andReturn('com.example.subscription');
        $serverNotification = $this->mock(ServerNotificationContract::class);
        $serverNotification->shouldReceive('getSubscription')->andReturn($subscription);
        $sut = new class($serverNotification) extends PurchaseEvent {
        };

        $this->assertSame('com.example.subscription', $sut->getSubscriptionId());
    }

    #[Test]
    public function get_subscription_identifier(): void
    {
        $subscription = $this->mock(SubscriptionContract::class);
        $subscription->shouldReceive('getUniqueIdentifier')->andReturn('com.example.subscription.123456');
        $serverNotification = $this->mock(ServerNotificationContract::class);
        $serverNotification->shouldReceive('getSubscription')->andReturn($subscription);
        $sut = new class($serverNotification) extends PurchaseEvent {
        };

        $this->assertSame('com.example.subscription.123456', $sut->getSubscriptionIdentifier());
    }
}
