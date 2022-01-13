<?php

namespace Imdhemy\Purchases\Subscriptions;

use Imdhemy\AppStore\Receipts\ReceiptResponse;
use Imdhemy\GooglePlay\Subscriptions\SubscriptionPurchase;
use Imdhemy\Purchases\ValueObjects\Time;

class AppGallerySubscription implements \Imdhemy\Purchases\Contracts\SubscriptionContract
{

    private $statusUpdateNotification;

    public function __construct($statusUpdateNotification)
    {
        $this->statusUpdateNotification = $statusUpdateNotification;
    }

    /**
     * @inheritDoc
     */
    public function getExpiryTime(): Time
    {
        return new Time($this->statusUpdateNotification->latestReceiptInfo->expirationDate);
    }

    /**
     * @inheritDoc
     */
    public function getItemId(): string
    {
        return $this->statusUpdateNotification->productId;
    }

    /**
     * @inheritDoc
     */
    public function getProvider(): string
    {
        return 'app_gallery';
    }

    /**
     * @inheritDoc
     */
    public function getUniqueIdentifier(): string
    {
        return $this->statusUpdateNotification->latestReceiptInfo->purchaseToken;
    }

    /**
     * @inheritDoc
     */
    public function getProviderRepresentation()
    {
        return $this->statusUpdateNotification;
    }
}
