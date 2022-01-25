<?php

namespace Imdhemy\Purchases\ServerNotifications;

use Huawei\IAP\Response\SubscriptionResponse;
use Imdhemy\Purchases\Contracts\ServerNotificationContract;
use Imdhemy\Purchases\Contracts\SubscriptionContract;
use Imdhemy\Purchases\Subscriptions\AppGallerySubscription;

class AppGalleryServerNotification implements ServerNotificationContract
{
    const NOTIFICATION_TYPES = [
        0 => 'INITIAL_BUY',
        1 => 'CANCEL',
        2 => 'RENEWAL',
        3 => 'INTERACTIVE_RENEWAL',
        4 => 'NEW_RENEWAL_PREF',
        5 => 'RENEWAL_STOPPED',
        6 => 'RENEWAL_RESTORED',
        7 => 'RENEWAL_RECURRING',
        9 => 'ON_HOLD',
        10 => 'PAUSED',
        11 => 'PAUSE_PLAN_CHANGED',
        12 => 'PRICE_CHANGE_CONFIRMED',
        13 => 'DEFERRED'
    ];

    /**
     * @array
     */
    private $statusUpdateNotification;

    /**
     * @var SubscriptionResponse
     */
    private $subscriptionResponse;

    public function __construct(\stdClass $statusUpdateNotification)
    {
        $this->statusUpdateNotification = $statusUpdateNotification;

        $this->subscriptionResponse = new SubscriptionResponse(['inappPurchaseData' => $this->statusUpdateNotification->latestReceiptInfo]);

        $this->statusUpdateNotification->latestReceiptInfo = json_decode($this->statusUpdateNotification->latestReceiptInfo);
    }

    public function getType(): string
    {
        return self::NOTIFICATION_TYPES[$this->statusUpdateNotification->notificationType];
    }

    public function getSubscription(array $jsonKey = []): SubscriptionContract
    {
        return new AppGallerySubscription($this->subscriptionResponse);
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
