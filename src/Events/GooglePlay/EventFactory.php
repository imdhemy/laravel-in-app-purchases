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
        $notificationType = (int)$notification->getType();
        $types = (new ReflectionClass(SubscriptionNotification::class))->getConstants();
        $type = array_search($notificationType, $types, true);
        $className = self::getClassName($type);
        assert(class_exists($className) && is_subclass_of($className, PurchaseEventContract::class));

        return new $className($notification);
    }

    public static function getClassName(string $type): string
    {
        $camelCaseName = ucfirst(Str::camel(strtolower($type)));
        $namespace = (new ReflectionClass(self::class))->getNamespaceName();

        return $namespace.'\\'.$camelCaseName;
    }
}
