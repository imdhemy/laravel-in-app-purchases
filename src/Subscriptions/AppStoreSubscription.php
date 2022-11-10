<?php

declare(strict_types=1);

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
     */
    public function __construct(LatestReceiptInfo $receipt)
    {
        $this->receipt = $receipt;
    }

    public function getExpiryTime(): Time
    {
        return Time::fromAppStoreTime($this->receipt->getExpiresDate());
    }

    public function getItemId(): string
    {
        return $this->receipt->getProductId();
    }

    public function getProvider(): string
    {
        return 'app_store';
    }

    public function getUniqueIdentifier(): string
    {
        return $this->receipt->getOriginalTransactionId();
    }

    public function getProviderRepresentation(): LatestReceiptInfo
    {
        return $this->receipt;
    }
}
