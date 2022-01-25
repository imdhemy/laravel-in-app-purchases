<?php

namespace Imdhemy\Purchases\Tests\ServerNotifications;

use Imdhemy\Purchases\Contracts\ServerNotificationContract;
use Imdhemy\Purchases\Contracts\SubscriptionContract;
use Imdhemy\Purchases\ServerNotifications\AppGalleryServerNotification;
use Imdhemy\Purchases\Tests\TestCase;

class AppGalleryServerNotificationTest extends TestCase
{
    /**
     * @var AppGalleryServerNotification
     */
    private $appGalleryServerNotification;

    /**
     * @inheritDoc
     */
    public function setUp(): void
    {
        parent::setUp();
        $serverNotificationBody = json_decode(file_get_contents(__DIR__.'/../appgallery-server-notification.json'));
        $this->appGalleryServerNotification = new AppGalleryServerNotification(json_decode($serverNotificationBody->statusUpdateNotification));
    }

    /**
     * @test
     */
    public function test_constructor()
    {
        $this->assertInstanceOf(ServerNotificationContract::class, $this->appGalleryServerNotification);
    }

    /**
     * @test
     */
    public function test_get_notification_type()
    {
        $this->assertEquals(
            AppGalleryServerNotification::NOTIFICATION_TYPES[4],
            $this->appGalleryServerNotification->getType()
        );
    }

    /**
     * @test
     */
    public function test_get_subscription()
    {
        $this->assertInstanceOf(SubscriptionContract::class, $this->appGalleryServerNotification->getSubscription());
    }

    /**
     * @test
     */
    public function test_is_test_env()
    {
        $this->assertTrue($this->appGalleryServerNotification->isTest());
    }

    /**
     * @test
     */
    public function test_get_bundle()
    {
        $this->assertNotNull($this->appGalleryServerNotification->getBundle());
    }
}
