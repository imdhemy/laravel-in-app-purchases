<?php

namespace Imdhemy\Purchases\ServerNotifications;

use GuzzleHttp\Client;
use Imdhemy\AppStore\ServerNotifications\ServerNotification;
use Imdhemy\AppStore\ValueObjects\LatestReceiptInfo;
use Imdhemy\Purchases\Contracts\ServerNotificationContract;
use Imdhemy\Purchases\Contracts\SubscriptionContract;
use Imdhemy\Purchases\Subscriptions\AppStoreSubscription;
use Imdhemy\Purchases\ValueObjects\Time;

class AppStoreServerNotification implements ServerNotificationContract
{
    /**
     * @var ServerNotification
     */
    private $notification;

    /**
     * AppStoreServerNotification constructor.
     *
     * @param ServerNotification $notification
     */
    public function __construct(ServerNotification $notification)
    {
        $this->notification = $notification;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->notification->getNotificationType();
    }

    /**
     * @param Client|null $client
     *
     * @return SubscriptionContract
     */
    public function getSubscription(?Client $client = null): SubscriptionContract
    {
        return new AppStoreSubscription($this->getFirstReceipt());
    }

    /**
     * @return bool
     */
    public function isTest(): bool
    {
        return false;
    }

    /**
     * @return LatestReceiptInfo|null
     */
    private function getFirstReceipt(): ?LatestReceiptInfo
    {
        return $this->notification->getUnifiedReceipt()->getLatestReceiptInfo()[0];
    }

    /**
     * @return bool
     */
    public function isAutoRenewal(): bool
    {
        return $this->notification->getAutoRenewStatus() === true;
    }

    /**
     * @return Time|null
     */
    public function getAutoRenewStatusChangeDate(): ?Time
    {
        $time = $this->notification->getAutoRenewStatusChangeDate();
        if (! is_null($time)) {
            return Time::fromAppStoreTime($time);
        }

        return null;
    }

    /**
     * @return string
     */
    public function getBundle(): string
    {
        return (string)$this->notification->getBid();
    }
}
