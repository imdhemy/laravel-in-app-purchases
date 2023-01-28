<?php

declare(strict_types=1);

namespace Imdhemy\Purchases\ServerNotifications;

use GuzzleHttp\ClientInterface;
use Imdhemy\AppStore\ServerNotifications\ServerNotification;
use Imdhemy\AppStore\ValueObjects\LatestReceiptInfo;
use Imdhemy\Purchases\Contracts\ServerNotificationContract;
use Imdhemy\Purchases\Contracts\SubscriptionContract;
use Imdhemy\Purchases\Subscriptions\AppStoreSubscription;
use Imdhemy\Purchases\ValueObjects\Time;

class AppStoreServerNotification implements ServerNotificationContract
{
    private ServerNotification $notification;

    /**
     * AppStoreServerNotification constructor.
     */
    public function __construct(ServerNotification $notification)
    {
        $this->notification = $notification;
    }

    public function getType(): string
    {
        return $this->notification->getNotificationType();
    }

    public function getSubscription(?ClientInterface $client = null): SubscriptionContract
    {
        $firstReceipt = $this->getFirstReceipt();
        assert($firstReceipt instanceof LatestReceiptInfo);

        return new AppStoreSubscription($firstReceipt);
    }

    public function isTest(): bool
    {
        return false;
    }

    private function getFirstReceipt(): ?LatestReceiptInfo
    {
        $unifiedReceipt = $this->notification->getUnifiedReceipt();

        if ($unifiedReceipt && is_array($receipts = $unifiedReceipt->getLatestReceiptInfo()) && ! empty($receipts)) {
            $latestReceiptInfo = $receipts[0];
            assert($latestReceiptInfo instanceof LatestReceiptInfo);

            return $latestReceiptInfo;
        }

        return null;
    }

    public function isAutoRenewal(): bool
    {
        return true === $this->notification->getAutoRenewStatus();
    }

    public function getAutoRenewStatusChangeDate(): ?Time
    {
        $time = $this->notification->getAutoRenewStatusChangeDate();
        if (! is_null($time)) {
            return Time::fromAppStoreTime($time);
        }

        return null;
    }

    public function getBundle(): string
    {
        return (string)$this->notification->getBid();
    }

    /**
     * Gets the notification payload.
     */
    public function getPayload(): array
    {
        return $this->notification->toArray();
    }

    public function getAutoRenewProductId(): ?string
    {
        return $this->notification->getAutoRenewProductId();
    }

    public function getProvider(): string
    {
        return self::PROVIDER_APP_STORE;
    }
}
