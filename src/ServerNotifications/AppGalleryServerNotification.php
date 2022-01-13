<?php

namespace Imdhemy\Purchases\ServerNotifications;

use Imdhemy\Purchases\Contracts\ServerNotificationContract;
use Imdhemy\Purchases\Contracts\SubscriptionContract;
use Imdhemy\Purchases\Subscriptions\AppGallerySubscription;

class AppGalleryServerNotification implements ServerNotificationContract
{

    private $statusUpdateNotification;

    public function __construct($statusUpdateNotification)
    {
        $this->statusUpdateNotification = $statusUpdateNotification;
        $this->statusUpdateNotification->latestReceiptInfo = json_decode($this->statusUpdateNotification->latestReceiptInfo);
    }

    public function getType(): string
    {
        return $this->statusUpdateNotification->notificationType;
    }

    public function getSubscription(array $jsonKey = []): SubscriptionContract
    {
        return new AppGallerySubscription($this->statusUpdateNotification);
    }

    public function isTest(): bool
    {
        if ($this->statusUpdateNotification->environment == 'PROD') {
            return false;
        } else {
            return true;
        }
    }

    public function getBundle(): string
    {
        return $this->statusUpdateNotification->latestReceiptInfo->packageName;
    }
}
