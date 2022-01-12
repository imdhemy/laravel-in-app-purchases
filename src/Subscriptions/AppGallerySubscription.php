<?php

namespace Imdhemy\Purchases\Subscriptions;

use Imdhemy\AppStore\Receipts\ReceiptResponse;
use Imdhemy\GooglePlay\Subscriptions\SubscriptionPurchase;
use Imdhemy\Purchases\ValueObjects\Time;

class AppGallerySubscription implements \Imdhemy\Purchases\Contracts\SubscriptionContract
{

    /**
     * @inheritDoc
     */
    public function getExpiryTime(): Time
    {
        // TODO: Implement getExpiryTime() method.
    }

    /**
     * @inheritDoc
     */
    public function getItemId(): string
    {
        // TODO: Implement getItemId() method.
    }

    /**
     * @inheritDoc
     */
    public function getProvider(): string
    {
        // TODO: Implement getProvider() method.
    }

    /**
     * @inheritDoc
     */
    public function getUniqueIdentifier(): string
    {
        // TODO: Implement getUniqueIdentifier() method.
    }

    /**
     * @inheritDoc
     */
    public function getProviderRepresentation()
    {
        // TODO: Implement getProviderRepresentation() method.
    }
}
