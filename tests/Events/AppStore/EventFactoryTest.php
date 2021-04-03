<?php

namespace Imdhemy\Purchases\Tests\Events\AppStore;

use Imdhemy\AppStore\ServerNotifications\ServerNotification;
use Imdhemy\Purchases\Contracts\PurchaseEventContract;
use Imdhemy\Purchases\Events\AppStore\EventFactory;
use Imdhemy\Purchases\Events\AppStore\Revoke;
use Imdhemy\Purchases\ServerNotifications\AppStoreServerNotification;
use PHPUnit\Framework\TestCase;

class EventFactoryTest extends TestCase
{
    /**
     * @test
     */
    public function test_create()
    {
        $path = realpath(__DIR__ . '/../../appstore-server-notification.json');
        $serverNotificationBody = json_decode(file_get_contents($path), true);

        $serverNotification = ServerNotification::fromArray($serverNotificationBody);
        $appStoreServerNotification = new AppStoreServerNotification($serverNotification);
        $this->assertInstanceOf(PurchaseEventContract::class, EventFactory::create($appStoreServerNotification));
    }

    /**
     * @test
     */
    public function test_it_creates_revoke_event()
    {
        $path = realpath(__DIR__ . '/../../appstore-server-notification.json');
        $serverNotificationBody = json_decode(file_get_contents($path), true);
        $serverNotificationBody['notification_type'] = ServerNotification::REVOKE;

        $serverNotification = ServerNotification::fromArray($serverNotificationBody);
        $appStoreServerNotification = new AppStoreServerNotification($serverNotification);

        $this->assertInstanceOf(PurchaseEventContract::class, EventFactory::create($appStoreServerNotification));
        $this->assertInstanceOf(Revoke::class, EventFactory::create($appStoreServerNotification));
    }
}
