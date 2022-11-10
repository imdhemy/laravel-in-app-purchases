<?php

declare(strict_types=1);

namespace Imdhemy\Purchases\Events\GooglePlay;

use Illuminate\Support\Str;
use Imdhemy\GooglePlay\DeveloperNotifications\SubscriptionNotification;
use Imdhemy\Purchases\Contracts\PurchaseEventContract;
use Imdhemy\Purchases\ServerNotifications\GoogleServerNotification;

class EventFactory
{
    public static function create(GoogleServerNotification $notification): PurchaseEventContract
    {
        $notificationType = $notification->getType();
        $types = (new \ReflectionClass(SubscriptionNotification::class))->getConstants();
        $type = array_search($notificationType, $types);
        $className = self::getClassName($type);

        return new $className($notification);
    }

    public static function getClassName($type): string
    {
        $camelCaseName = ucfirst(Str::camel(strtolower($type)));

        return "\Imdhemy\Purchases\Events\GooglePlay\\".$camelCaseName;
    }
}
