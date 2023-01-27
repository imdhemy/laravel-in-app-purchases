<?php

declare(strict_types=1);

namespace Imdhemy\Purchases\Tests\Events;

use Imdhemy\AppStore\ServerNotifications\ServerNotification as AppStoreNotification;
use Imdhemy\GooglePlay\DeveloperNotifications\SubscriptionNotification as GoogleNotification;
use Imdhemy\Purchases\Contracts\ServerNotificationContract as ServerNotification;
use Imdhemy\Purchases\Events\AppStore\Cancel;
use Imdhemy\Purchases\Events\EventFactory;
use Imdhemy\Purchases\Events\GooglePlay\SubscriptionExpired;
use PHPUnit\Framework\TestCase;

class EventFactoryTest extends TestCase
{
    /**
     * @test
     */
    public function create_google_play_event(): void
    {
        $serverNotification = $this->createMock(ServerNotification::class);
        $serverNotification->method('getProvider')->willReturn(ServerNotification::PROVIDER_GOOGLE_PLAY);
        $serverNotification->method('getType')->willReturn((string)GoogleNotification::SUBSCRIPTION_EXPIRED);

        $event = (new EventFactory())->create($serverNotification);

        $this->assertInstanceOf(SubscriptionExpired::class, $event);
    }

    /**
     * @test
     */
    public function create_app_store_event(): void
    {
        $serverNotification = $this->createMock(ServerNotification::class);
        $serverNotification->method('getProvider')->willReturn(ServerNotification::PROVIDER_APP_STORE);
        $serverNotification->method('getType')->willReturn(AppStoreNotification::CANCEL);

        $event = (new EventFactory())->create($serverNotification);

        $this->assertInstanceOf(Cancel::class, $event);
    }
}
