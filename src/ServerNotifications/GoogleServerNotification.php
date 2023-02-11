<?php

declare(strict_types=1);

namespace Imdhemy\Purchases\ServerNotifications;

use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\GuzzleException;
use Imdhemy\GooglePlay\DeveloperNotifications\DeveloperNotification;
use Imdhemy\Purchases\Contracts\ServerNotificationContract;
use Imdhemy\Purchases\Contracts\SubscriptionContract;
use Imdhemy\Purchases\Subscriptions\GoogleSubscription;

/**
 * Class GoogleServerNotification.
 */
class GoogleServerNotification implements ServerNotificationContract
{
    public const TESTING_NOTIFICATION = -1;

    private DeveloperNotification $notification;

    /**
     * GoogleServerNotification constructor.
     */
    public function __construct(DeveloperNotification $notification)
    {
        $this->notification = $notification;
    }

    public function getType(): string
    {
        $type = $this->isTest() ?
            self::TESTING_NOTIFICATION :
            $this->notification->getPayload()->getNotificationType();

        return (string)$type;
    }

    /**
     * @throws GuzzleException
     */
    public function getSubscription(?ClientInterface $client = null): SubscriptionContract
    {
        return GoogleSubscription::createFromDeveloperNotification($this->notification, $client);
    }

    public function isTest(): bool
    {
        return $this->notification->isTestNotification();
    }

    public function getBundle(): string
    {
        return $this->notification->getPackageName();
    }

    /**
     * Gets the notification payload.
     */
    public function getPayload(): array
    {
        return $this->notification->toArray();
    }

    public function getProvider(): string
    {
        return self::PROVIDER_GOOGLE_PLAY;
    }
}
