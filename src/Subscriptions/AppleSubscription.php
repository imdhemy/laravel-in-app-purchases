<?php

declare(strict_types=1);

namespace Imdhemy\Purchases\Subscriptions;

use Imdhemy\AppStore\ServerNotifications\V2DecodedPayload;
use Imdhemy\Purchases\Contracts\SubscriptionContract;
use Imdhemy\Purchases\ValueObjects\Time;

/**
 * Class AppleSubscription
 * This class represents a subscription from Apple and wraps the V2DecodedPayload.
 */
class AppleSubscription implements SubscriptionContract
{
    private V2DecodedPayload $payload;

    /**
     * Prevents the creation of the object from outside the class.
     */
    private function __construct(V2DecodedPayload $payload)
    {
        $this->payload = $payload;
    }

    public static function fromV2DecodedPayload(V2DecodedPayload $payload): self
    {
        return new self($payload);
    }

    public function getExpiryTime(): Time
    {
        $time = $this->payload->getTransactionInfo()->getExpiresDate();
        assert(! is_null($time));

        return Time::fromAppStoreTime($time);
    }

    public function getItemId(): string
    {
        return (string)$this->payload->getTransactionInfo()->getProductId();
    }

    public function getProvider(): string
    {
        return SubscriptionContract::PROVIDER_APP_STORE;
    }

    public function getUniqueIdentifier(): string
    {
        return (string)$this->payload->getTransactionInfo()->getOriginalTransactionId();
    }

    public function getProviderRepresentation(): V2DecodedPayload
    {
        return $this->payload;
    }
}
