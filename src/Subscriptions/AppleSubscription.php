<?php

namespace Imdhemy\Purchases\Subscriptions;

use Imdhemy\AppStore\ServerNotifications\V2DecodedPayload;
use Imdhemy\Purchases\Contracts\SubscriptionContract;
use Imdhemy\Purchases\ValueObjects\Time;

/**
 * Class AppleSubscription
 * This class represents a subscription from Apple and wraps the V2DecodedPayload
 */
class AppleSubscription implements SubscriptionContract
{
    /**
     * @var V2DecodedPayload
     */
    private V2DecodedPayload $payload;

    /**
     * Prevents the creation of the object from outside the class
     *
     * @param V2DecodedPayload $payload
     */
    private function __construct(V2DecodedPayload $payload)
    {
        $this->payload = $payload;
    }

    /**
     * @param V2DecodedPayload $payload
     *
     * @return static
     */
    public static function fromV2DecodedPayload(V2DecodedPayload $payload): self
    {
        return new self($payload);
    }

    /**
     * @return Time
     */
    public function getExpiryTime(): Time
    {
        return Time::fromAppStoreTime($this->payload->getTransactionInfo()->getExpiresDate());
    }

    /**
     * @return string
     */
    public function getItemId(): string
    {
        return (string)$this->payload->getTransactionInfo()->getProductId();
    }

    /**
     * @return string
     */
    public function getProvider(): string
    {
        return SubscriptionContract::PROVIDER_APP_STORE;
    }

    /**
     * @return string
     */
    public function getUniqueIdentifier(): string
    {
        return (string)$this->payload->getTransactionInfo()->getOriginalTransactionId();
    }

    /**
     * @return V2DecodedPayload
     */
    public function getProviderRepresentation(): V2DecodedPayload
    {
        return $this->payload;
    }
}
