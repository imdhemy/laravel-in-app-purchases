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
    protected const NAMESPACES = [
        'google_play' => 'Imdhemy\Purchases\Events\GooglePlay',
        'app_store' => 'Imdhemy\Purchases\Events\AppStore',
    ];

    public function create(ServerNotification $notification): PurchaseEvent
    {
        $provider = $notification->getProvider();
        assert(
            array_key_exists($notification->getProvider(), self::NAMESPACES),
            new LogicException("Unknown provider: $provider")
        );

        $type = $notification->getType();
        if (ServerNotification::PROVIDER_GOOGLE_PLAY === $provider) {
            $notificationType = (int)$notification->getType();
            $types = (new ReflectionClass(SubscriptionNotification::class))->getConstants();
            $type = (string)array_search($notificationType, $types, true);
        }

        $className = (string)Str::of($type)
            ->lower()
            ->studly()
            ->prepend(self::NAMESPACES[$provider].'\\');

        if (! class_exists($className)) {
            return new FallbackEvent($notification);
        }

        assert(
            is_a($className, PurchaseEvent::class, true),
            new LogicException("Invalid event class: $className")
        );

        return new $className($notification);
    }
}
