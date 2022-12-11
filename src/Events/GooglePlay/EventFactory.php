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

        $className = self::getClassName($type);
        $event = new $className($notification);
        assert($event instanceof PurchaseEventContract);

        return $event;
    }

    /**
     * Returns the event class name for the given type.
     *
     * @psalm-suppress MoreSpecificReturnType
     * @psalm-suppress LessSpecificReturnStatement
     *
     * @psalm-return class-string<PurchaseEventContract>
     */
    public static function getClassName(string $type): string
    {
        $camelCaseName = ucfirst(Str::camel(strtolower($type)));
        $classString = __NAMESPACE__.'\\'.$camelCaseName;
        assert(class_exists($classString));

        return $classString;
    }
}
