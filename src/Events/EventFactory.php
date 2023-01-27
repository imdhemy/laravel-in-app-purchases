<?php

declare(strict_types=1);

namespace Imdhemy\Purchases\Events;

use Illuminate\Support\Str;
use Imdhemy\GooglePlay\DeveloperNotifications\SubscriptionNotification;
use Imdhemy\Purchases\Contracts\EventFactory as EventFactoryContract;
use Imdhemy\Purchases\Contracts\PurchaseEventContract as PurchaseEvent;
use Imdhemy\Purchases\Contracts\ServerNotificationContract as ServerNotification;
use LogicException;
use ReflectionClass;

/**
 * This class is responsible for creating events from the given server notification
 * It should replace all vendor specific event factories.
 */
class EventFactory implements EventFactoryContract
{
    private const NAMESPACES = [
        'google_play' => 'Imdhemy\Purchases\Events\GooglePlay',
        'app_store' => 'Imdhemy\Purchases\Events\AppStore',
    ];

    public function create(ServerNotification $notification): PurchaseEvent
    {
        assert(
            array_key_exists($notification->getProvider(), self::NAMESPACES),
            new LogicException("Unknown provider: {$notification->getProvider()}")
        );

        $eventClass = ServerNotification::PROVIDER_GOOGLE_PLAY === $notification->getProvider()
            ? $this->googleEvent($notification)
            : $this->appStoreEvent($notification);

        assert(
            is_subclass_of($eventClass, PurchaseEvent::class),
            new LogicException(
                "Class $eventClass must implement ".PurchaseEvent::class
            )
        );

        return new $eventClass($notification);
    }

    /**
     * @return class-string<PurchaseEvent>
     */
    private function googleEvent(ServerNotification $notification): string
    {
        $notificationType = (int)$notification->getType();
        $types = (new ReflectionClass(SubscriptionNotification::class))->getConstants();
        $type = array_search($notificationType, $types, true);
        assert(false !== $type, new LogicException("Unknown notification type: $notificationType"));
        $camelCaseName = (string)Str::of($type)->lower()->studly();
        $className = self::NAMESPACES[$notification->getProvider()]."\\$camelCaseName";

        assert(class_exists($className), new LogicException("Class $className does not exist"));

        return $className;
    }

    /**
     * @return class-string<PurchaseEvent>
     */
    private function appStoreEvent(ServerNotification $notification): string
    {
        $className = (string)Str::of($notification->getType())
            ->lower()
            ->studly()
            ->prepend(self::NAMESPACES[$notification->getProvider()].'\\');

        assert(class_exists($className), new LogicException("Class $className does not exist"));

        return $className;
    }
}
