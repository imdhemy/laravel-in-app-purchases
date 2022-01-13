<?php

namespace Imdhemy\Purchases\Events\AppGallery;

use Illuminate\Support\Str;
use Imdhemy\Purchases\Contracts\PurchaseEventContract;
use Imdhemy\Purchases\ServerNotifications\AppGalleryServerNotification;

class EventFactory
{
    /**
     * @param AppGalleryServerNotification $notification
     * @return PurchaseEventContract
     */
    public static function create(AppGalleryServerNotification $notification): PurchaseEventContract
    {
        $type = $notification->getType();
        $className = "\Imdhemy\Purchases\Events\AppGallery\\" . ucfirst(Str::camel(strtolower($type)));

        return new $className($notification);
    }
}
