<?php

declare(strict_types=1);

namespace Imdhemy\Purchases\ServerNotifications;

use GuzzleHttp\ClientInterface;
use Imdhemy\AppStore\ServerNotifications\V2DecodedPayload;
use Imdhemy\Purchases\Contracts\HasSubtype;
use Imdhemy\Purchases\Contracts\ServerNotificationContract;
use Imdhemy\Purchases\Contracts\SubscriptionContract;
use Imdhemy\Purchases\Subscriptions\AppleSubscription;

class AppStoreV2ServerNotification implements ServerNotificationContract, HasSubtype
{
    private V2DecodedPayload $payload;

    /**
     * Prevents the creation of the object from outside the class.
     */
    private function __construct(V2DecodedPayload $payload)
    {
        $this->payload = $payload;
    }

    /**
     * Static constructor.
     */
    public static function fromDecodedPayload(V2DecodedPayload $decodedPayload): self
    {
        return new self($decodedPayload);
    }

    #[\Override]
    public function getType(): string
    {
        return $this->payload->getType();
    }

    #[\Override]
    public function getSubscription(?ClientInterface $client = null): SubscriptionContract
    {
        return AppleSubscription::fromV2DecodedPayload($this->payload);
    }

    #[\Override]
    public function isTest(): bool
    {
        return V2DecodedPayload::TYPE_TEST === $this->payload->getType();
    }

    #[\Override]
    public function getBundle(): string
    {
        return $this->payload->getAppMetadata()->bundleId();
    }

    /**
     * Gets the notification payload.
     */
    #[\Override]
    public function getPayload(): array
    {
        return $this->payload->toArray();
    }

    /**
     * Gets subscription subtype.
     */
    #[\Override]
    public function getSubtype(): string
    {
        return (string)$this->payload->getSubType();
    }

    #[\Override]
    public function getProvider(): string
    {
        return self::PROVIDER_APP_STORE;
    }
}
