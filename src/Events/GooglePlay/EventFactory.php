<?php

declare(strict_types=1);

namespace Imdhemy\Purchases\Events\GooglePlay;

use Illuminate\Support\Str;
use Imdhemy\GooglePlay\DeveloperNotifications\SubscriptionNotification;
use Imdhemy\Purchases\Contracts\PurchaseEventContract;
use Imdhemy\Purchases\ServerNotifications\GoogleServerNotification;
use ReflectionClass;

class EventFactory
{
    public static function create(GoogleServerNotification $notification): PurchaseEventContract
    {
        $notificationType = $notification->getType();
        $types = (new ReflectionClass(SubscriptionNotification::class))->getConstants();
        $type = array_search((int)$notificationType, $types, true);
        assert(! is_bool($type));

        $classString = __NAMESPACE__.'\\'.ucfirst(Str::camel(strtolower($type)));
        assert(class_exists($classString) && is_subclass_of($classString, PurchaseEventContract::class));

        return new $classString($notification);
    }
}
