<?php

namespace Imdhemy\Purchases\Subscriptions;

use Imdhemy\AppStore\ValueObjects\LatestReceiptInfo;
use Imdhemy\Purchases\Contracts\SubscriptionContract;
use Imdhemy\Purchases\ValueObjects\Time;

class AppStoreSubscription implements SubscriptionContract
{
    /**
     * @var LatestReceiptInfo
     */
    private $receipt;

    /**
     * AppStoreSubscription constructor.
     *
     * @param LatestReceiptInfo $receipt
     */
    public function __construct(LatestReceiptInfo $receipt)
    {
        $this->receipt = $receipt;
    }

    /**
     * @return Time
     */
    public function getExpiryTime(): Time
    {
        return Time::fromAppStoreTime($this->receipt->getExpiresDate());
    }

    /**
     * @return string
     */
    public function getItemId(): string
    {
        return $this->receipt->getProductId();
    }

    /**
     * @return string
     */
    public function getProvider(): string
    {
        return 'app_store';
    }

    /**
     * @return string
     */
    public function getUniqueIdentifier(): string
    {
        return $this->receipt->getOriginalTransactionId();
    }

    /**
     * @return LatestReceiptInfo
     */
    public function getProviderRepresentation(): LatestReceiptInfo
    {
        return $this->receipt;
    }
}
