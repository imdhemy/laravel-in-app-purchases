<?php

namespace Imdhemy\Purchases\Tests\Events\AppGallery;

use CHfur\AppGallery\ServerNotifications\ServerNotification;
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
        $serverNotificationBody = json_decode(file_get_contents(__DIR__.'/../../appgallery-server-notification.json'), true);
        $publicKey = file_get_contents(__DIR__.'/../../appgallery_public_key');
        $serverNotification = ServerNotification::parse($serverNotificationBody, $publicKey);
        $appGalleryServerNotification = new AppGalleryServerNotification($serverNotification);

        $this->assertInstanceOf(PurchaseEventContract::class, EventFactory::create($appGalleryServerNotification));
    }

    /**
     * @test
     */
    public function test_it_creates_new_renewal_pref_event()
    {
        $serverNotificationBody = json_decode(file_get_contents(__DIR__.'/../../appgallery-server-notification.json'), true);
        $publicKey = file_get_contents(__DIR__.'/../../appgallery_public_key');
        $serverNotification = ServerNotification::parse($serverNotificationBody, $publicKey);
        $appGalleryServerNotification = new AppGalleryServerNotification($serverNotification);

        $this->assertInstanceOf(PurchaseEventContract::class, EventFactory::create($appGalleryServerNotification));
        $this->assertInstanceOf(NewRenewalPref::class, EventFactory::create($appGalleryServerNotification));
    }
}
