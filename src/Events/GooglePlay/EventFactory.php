<?php


namespace Imdhemy\Purchases\Events\GooglePlay;

use Illuminate\Support\Str;
use Imdhemy\GooglePlay\DeveloperNotifications\DeveloperNotification;
use Imdhemy\GooglePlay\DeveloperNotifications\SubscriptionNotification;
use Imdhemy\Purchases\Events\PurchaseEvent;
use ReflectionClass;
use ReflectionException;

class EventFactory
{
    /**
     * @param DeveloperNotification $notification
     * @return PurchaseEvent
     * @throws ReflectionException
     */
    public static function create(DeveloperNotification $notification): PurchaseEvent
    {
        $notificationType = $notification->getSubscriptionNotification()->getNotificationType();
        $types = (new ReflectionClass(SubscriptionNotification::class))->getConstants();
        $type = array_search($notificationType, $types);
        $className = self::getClassName($type);

        return new $className($notification);
    }

    /**
     * @param $type
     * @return string
     */
    public static function getClassName($type): string
    {
        $camelCaseName = ucfirst(Str::camel(strtolower($type)));

        return "\Imdhemy\Purchases\Events\GooglePlay\\" . $camelCaseName;
    }
}
