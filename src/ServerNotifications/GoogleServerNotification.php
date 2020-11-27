<?php


namespace Imdhemy\Purchases\ServerNotifications;

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
    /**
     * @var GoogleSubscription
     */
    private $googleSubscription;

    /**
     * @var DeveloperNotification
     */
    protected $notification;

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
        return $this->notification->getSubscriptionNotification()->getNotificationType();
    }

    /**
     * @return SubscriptionContract
     * @throws GuzzleException
     */
    public function getSubscription(): SubscriptionContract
    {
        if (is_null($this->googleSubscription)) {
            $this->googleSubscription = GoogleSubscription::createFromDeveloperNotification(
                $this->notification
            );
        }

        return $this->googleSubscription;
    }
}
