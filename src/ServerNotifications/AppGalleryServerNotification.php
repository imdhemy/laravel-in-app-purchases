<?php

namespace Imdhemy\Purchases\ServerNotifications;

use Imdhemy\Purchases\Contracts\ServerNotificationContract;
use Imdhemy\Purchases\Contracts\SubscriptionContract;

class AppGalleryServerNotification implements ServerNotificationContract
{

    private $statusUpdateNotification;

    public function __construct($statusUpdateNotification)
    {
        $this->statusUpdateNotification = $statusUpdateNotification;
    }

    public function getType(): string
    {
        return $this->statusUpdateNotification->notificationType;
    }

    public function getSubscription(array $jsonKey = []): SubscriptionContract
    {
        // TODO: Implement getSubscription() method.
    }

    public function isTest(): bool
    {
        // TODO: Implement isTest() method.
    }

    public function getBundle(): string
    {
        // TODO: Implement getBundle() method.
    }
}
