<?php

namespace Imdhemy\Purchases\Tests\Events\AppGallery;

use Imdhemy\Purchases\Contracts\PurchaseEventContract;
use Imdhemy\Purchases\Events\AppGallery\EventFactory;
use Imdhemy\Purchases\Events\AppGallery\NewRenewalPref;
use Imdhemy\Purchases\ServerNotifications\AppGalleryServerNotification;
use PHPUnit\Framework\TestCase;

class EventFactoryTest extends TestCase
{
    /**
     * @test
     */
    public function test_create()
    {
        $serverNotificationBody = json_decode(file_get_contents(__DIR__.'/../../appgallery-server-notification.json'));
        $serverNotification = new AppGalleryServerNotification(json_decode($serverNotificationBody->statusUpdateNotification));

        $this->assertInstanceOf(PurchaseEventContract::class, EventFactory::create($serverNotification));
    }

    /**
     * @test
     */
    public function test_it_creates_new_renewal_pref_event()
    {
        $serverNotificationBody = json_decode(file_get_contents(__DIR__.'/../../appgallery-server-notification.json'));
        $serverNotification = new AppGalleryServerNotification(json_decode($serverNotificationBody->statusUpdateNotification));

        $this->assertInstanceOf(PurchaseEventContract::class, EventFactory::create($serverNotification));
        $this->assertInstanceOf(NewRenewalPref::class, EventFactory::create($serverNotification));
    }
}
