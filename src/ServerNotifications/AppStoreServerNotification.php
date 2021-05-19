<?php


namespace Imdhemy\Purchases\ServerNotifications;

use Imdhemy\AppStore\ServerNotifications\ServerNotification;
use Imdhemy\AppStore\ValueObjects\ReceiptInfo;
use Imdhemy\Purchases\Contracts\ServerNotificationContract;
use Imdhemy\Purchases\Contracts\SubscriptionContract;
use Imdhemy\Purchases\Subscriptions\AppStoreSubscription;

class AppStoreServerNotification implements ServerNotificationContract
{
    /**
     * @var ServerNotification
     */
    private $notification;

    /**
     * AppStoreServerNotification constructor.
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
     * @return SubscriptionContract
     */
    public function getSubscription(): SubscriptionContract
    {
        return new AppStoreSubscription($this->getFirstReceipt());
    }

    /**
     * @return bool
     */
    public function isTest(): bool
    {
        return ServerNotification::ENV_SANDBOX === $this->notification->environment;
    }

    /**
     * @return ReceiptInfo
     */
    private function getFirstReceipt(): ReceiptInfo
    {
        return $this->notification->getUnifiedReceipt()->getLatestReceiptInfo()[0];
    }
}
