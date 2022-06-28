<?php

namespace Tests\ServerNotifications;

use Imdhemy\AppStore\ServerNotifications\ServerNotification;
use Imdhemy\Purchases\Contracts\ServerNotificationContract;
use Imdhemy\Purchases\Contracts\SubscriptionContract;
use Imdhemy\Purchases\ServerNotifications\AppStoreServerNotification;
use Imdhemy\Purchases\ValueObjects\Time;
use Tests\TestCase;

class AppStoreServerNotificationTest extends TestCase
{
    /**
     * @var AppStoreServerNotification
     */
    private $appStoreServerNotification;

    /**
     * @inheritDoc
     */
    public function setUp(): void
    {
        parent::setUp();
        $path = $this->testAssetPath('appstore-server-notification.json');
        $serverNotificationBody = json_decode(file_get_contents($path), true);

        $serverNotification = ServerNotification::fromArray($serverNotificationBody);
        $this->appStoreServerNotification = new AppStoreServerNotification($serverNotification);
    }

    /**
     * @test
     */
    public function test_constructor()
    {
        $this->assertInstanceOf(ServerNotificationContract::class, $this->appStoreServerNotification);
    }

    /**
     * @test
     */
    public function test_get_notification_type()
    {
        $this->assertEquals(
            ServerNotification::DID_CHANGE_RENEWAL_STATUS,
            $this->appStoreServerNotification->getType()
        );
    }

    /**
     * @test
     */
    public function test_get_subscription()
    {
        $this->assertInstanceOf(SubscriptionContract::class, $this->appStoreServerNotification->getSubscription());
    }

    /**
     * @test
     */
    public function test_get_change_renewal_status_data()
    {
        $isAutoRenewal = $this->appStoreServerNotification->isAutoRenewal();
        $changeDate = $this->appStoreServerNotification->getAutoRenewStatusChangeDate();

        $this->assertFalse($isAutoRenewal);
        $this->assertInstanceOf(Time::class, $changeDate);
    }

    /**
     * @test
     */
    public function test_get_bundle()
    {
        $this->assertNotNull($this->appStoreServerNotification->getBundle());
    }
}
