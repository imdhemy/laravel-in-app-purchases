<?php


namespace Imdhemy\Purchases\ServerNotifications;

use GuzzleHttp\Exception\GuzzleException;
use Imdhemy\GooglePlay\DeveloperNotifications\SubscriptionNotification;
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
     * @var SubscriptionNotification
     */
    protected $notification;

    /**
     * @var string
     */
    protected $packageName;

    /**
     * @var GoogleSubscription
     */
    private $googleSubscription;

    /**
     * GoogleServerNotification constructor.
     * @param SubscriptionNotification $notification
     * @param string $packageName
     */
    public function __construct(SubscriptionNotification $notification, string $packageName)
    {
        $this->notification = $notification;
        $this->packageName = $packageName;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->notification->getNotificationType();
    }

    /**
     * @return SubscriptionContract
     * @throws GuzzleException
     */
    public function getSubscription(): SubscriptionContract
    {
        if (is_null($this->googleSubscription)) {
            $this->googleSubscription = GoogleSubscription::create(
                $this->packageName,
                $this->notification
            );
        }

        return $this->googleSubscription;
    }
}
