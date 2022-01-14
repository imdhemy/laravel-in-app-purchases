<?php

namespace Imdhemy\Purchases\Subscriptions;

use Huawei\IAP\Response\SubscriptionResponse;
use Imdhemy\Purchases\Contracts\SubscriptionContract;
use Imdhemy\Purchases\ValueObjects\Time;

class AppGallerySubscription implements SubscriptionContract
{

    /**
     * @var SubscriptionResponse
     */
    private $subscriptionResponse;

    public function __construct(SubscriptionResponse $subscriptionResponse)
    {
        $this->subscriptionResponse = $subscriptionResponse;
    }

    /**
     * @inheritDoc
     */
    public function getExpiryTime(): Time
    {
        return new Time($this->subscriptionResponse->getExpirationDate());
    }

    /**
     * @inheritDoc
     */
    public function getItemId(): string
    {
        return $this->subscriptionResponse->getProductId();
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
        return $this->subscriptionResponse->getPurchaseToken();
    }

    /**
     * @inheritDoc
     */
    public function getProviderRepresentation()
    {
        return $this->subscriptionResponse;
    }
}
