<?php

namespace Imdhemy\Purchases\ServerNotifications;

use CHfur\AppGallery\ServerNotifications\ServerNotification;
use CHfur\AppGallery\ServerNotifications\SubscriptionNotification;
use Imdhemy\Purchases\Contracts\ServerNotificationContract;
use Imdhemy\Purchases\Contracts\SubscriptionContract;
use Imdhemy\Purchases\Subscriptions\AppGallerySubscription;

class AppGalleryServerNotification implements ServerNotificationContract
{
    /**
     * @var SubscriptionNotification
     */
    private $subscriptionNotification;

    /**
     * AppGalleryServerNotification constructor.
     * @param  ServerNotification  $notification
     */
    public function __construct(ServerNotification $notification)
    {
        $this->subscriptionNotification = $notification->getSubscriptionNotification();
    }

    public function getType(): string
    {
        return $this->subscriptionNotification->getNotificationTypeName();
    }

    public function getSubscription(array $jsonKey = []): SubscriptionContract
    {
        return new AppGallerySubscription($this->subscriptionNotification->getSubscriptionResponse());
    }

    public function isTest(): bool
    {
        return $this->subscriptionNotification->isSandbox();
    }

    public function getBundle(): string
    {
        return $this->subscriptionNotification->getSubscriptionResponse()->getPackageName() ?? '';
    }
}
