<?php

namespace Imdhemy\Purchases\ServerNotifications;

use GuzzleHttp\Client;
use Imdhemy\AppStore\ServerNotifications\V2DecodedPayload;
use Imdhemy\Purchases\Contracts\HasSubtype;
use Imdhemy\Purchases\Contracts\ServerNotificationContract;
use Imdhemy\Purchases\Contracts\SubscriptionContract;
use Imdhemy\Purchases\Subscriptions\AppleSubscription;

class AppStoreV2ServerNotification implements ServerNotificationContract, HasSubtype
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
     * Static constructor
     *
     * @param V2DecodedPayload $decodedPayload
     *
     * @return static
     */
    public static function fromDecodedPayload(V2DecodedPayload $decodedPayload): self
    {
        return new self($decodedPayload);
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->payload->getType();
    }

    /**
     * @param Client|null $client
     *
     * @return SubscriptionContract
     */
    public function getSubscription(?Client $client = null): SubscriptionContract
    {
        return AppleSubscription::fromV2DecodedPayload($this->payload);
    }

    /**
     * @return bool
     */
    public function isTest(): bool
    {
        return $this->payload->getType() === V2DecodedPayload::TYPE_TEST;
    }

    /**
     * @return string
     */
    public function getBundle(): string
    {
        return $this->payload->getAppMetadata()->bundleId();
    }

    /**
     * Gets the notification payload
     *
     * @return array
     */
    public function getPayload(): array
    {
        return $this->payload->toArray();
    }

    /**
     * Gets subscription subtype
     *
     * @return string
     */
    public function getSubtype(): string
    {
        return (string)$this->payload->getSubType();
    }
}
