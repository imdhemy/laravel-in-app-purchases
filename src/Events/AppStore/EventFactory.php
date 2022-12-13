<?php

declare(strict_types=1);

namespace Imdhemy\Purchases\Events\AppStore;

use Illuminate\Support\Str;
use Imdhemy\Purchases\Contracts\PurchaseEventContract;
use Imdhemy\Purchases\Contracts\ServerNotificationContract;

class EventFactory
{
    public static function create(ServerNotificationContract $notification): PurchaseEventContract
    {
        $type = $notification->getType();
        $className = __NAMESPACE__.'\\'.ucfirst(Str::camel(strtolower($type)));
        assert(class_exists($className) && is_subclass_of($className, PurchaseEventContract::class));

        return new $className($notification);
    }
}
