<?php

declare(strict_types=1);

namespace Imdhemy\Purchases\Events\GooglePlay;

use Illuminate\Support\Str;
use Imdhemy\GooglePlay\DeveloperNotifications\SubscriptionNotification;
use Imdhemy\Purchases\Contracts\PurchaseEventContract;
use Imdhemy\Purchases\ServerNotifications\GoogleServerNotification;
use LogicException;
use ReflectionClass;

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
    public static function create(GoogleServerNotification $notification): PurchaseEventContract
    {
        $notificationType = (int)$notification->getType();
        $types = (new ReflectionClass(SubscriptionNotification::class))->getConstants();
        $type = array_search($notificationType, $types, true);
        assert(false !== $type, new LogicException("Unknown notification type: $notificationType"));
        $camelCaseName = ucfirst(Str::camel(strtolower($type)));
        $className = __NAMESPACE__."\\$camelCaseName";
        assert(class_exists($className) && is_subclass_of($className, PurchaseEventContract::class));

        return new $className($notification);
    }
}
