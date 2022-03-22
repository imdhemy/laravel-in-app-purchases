<?php

namespace Imdhemy\Purchases\ServerNotifications;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Imdhemy\GooglePlay\DeveloperNotifications\DeveloperNotification;
use Imdhemy\Purchases\Contracts\ServerNotificationContract;
use Imdhemy\Purchases\Contracts\SubscriptionContract;
use Imdhemy\Purchases\Subscriptions\GoogleSubscription;

/**
 * Class GoogleServerNotification
 * @package Imdhemy\Purchases\ServerNotifications
 */
class GoogleServerNotification implements ServerNotificationContract
{
    public const TESTING_NOTIFICATION = -1;

    /**
     * @var DeveloperNotification
     */
    private $notification;

    /**
     * GoogleServerNotification constructor.
     * @param DeveloperNotification $notification
     */
    public function __construct(DeveloperNotification $notification)
    {
        $this->notification = $notification;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        $type = $this->isTest() ?
            self::TESTING_NOTIFICATION :
            $this->notification->getPayload()->getNotificationType();

        return (string)$type;
    }

    /**
     * @param Client|null $client
     * @return SubscriptionContract
     * @throws GuzzleException
     */
    public function getSubscription(?Client $client = null): SubscriptionContract
    {
        return GoogleSubscription::createFromDeveloperNotification($this->notification, $client);
    }

    /**
     * @return bool
     */
    public function isTest(): bool
    {
        return $this->notification->isTestNotification();
    }

    /**
     * @return string
     */
    public function getBundle(): string
    {
        return $this->notification->getPackageName();
    }
}
