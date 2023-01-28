<?php

declare(strict_types=1);

namespace Imdhemy\Purchases\Events\AppStore;

use Illuminate\Support\Str;
use Imdhemy\Purchases\Contracts\PurchaseEventContract;
use Imdhemy\Purchases\Contracts\ServerNotificationContract;

/**
 * @deprecated Use \Imdhemy\Purchases\Events\EventFactory instead
 * @see \Imdhemy\Purchases\Events\EventFactory
 */
class EventFactory
{
    /**
     * @deprecated Use \Imdhemy\Purchases\Events\EventFactory::create() instead
     * @see \Imdhemy\Purchases\Events\EventFactory::create()
     */
    public static function create(ServerNotificationContract $notification): PurchaseEventContract
    {
        $type = $notification->getType();
        $className = "\Imdhemy\Purchases\Events\AppStore\\".ucfirst(Str::camel(strtolower($type)));
        assert(class_exists($className) && is_subclass_of($className, PurchaseEventContract::class));

        return new $className($notification);
    }
}
