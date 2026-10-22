<?php

declare(strict_types=1);

namespace Imdhemy\Purchases\Events\AppStore;

use DateTimeImmutable;
use Imdhemy\AppStore\ServerNotifications\V2DecodedPayload;
use Imdhemy\AppStore\ValueObjects\JwsRenewalInfo;
use Imdhemy\Purchases\Events\PurchaseEvent;
use Imdhemy\Purchases\ServerNotifications\AppStoreServerNotification;
use Imdhemy\Purchases\ServerNotifications\AppStoreV2ServerNotification;
use Imdhemy\Purchases\ValueObjects\Time;

/**
 * @method AppStoreServerNotification|AppStoreV2ServerNotification getServerNotification()
 */
class DidChangeRenewalStatus extends PurchaseEvent
{
    public function isAutoRenewal(): bool
    {
        assert(
            $this->serverNotification instanceof AppStoreServerNotification
            || $this->serverNotification instanceof AppStoreV2ServerNotification
        );

        if ($this->serverNotification instanceof AppStoreServerNotification) {
            return $this->serverNotification->isAutoRenewal();
        }

        $payload = V2DecodedPayload::fromArray($this->serverNotification->getPayload());

        return JwsRenewalInfo::AUTO_RENEW_STATUS_ON === $payload->getRenewalInfo()->getAutoRenewStatus();
    }

    public function getAutoRenewStatusChangedAt(): ?DateTimeImmutable
    {
        assert(
            $this->serverNotification instanceof AppStoreServerNotification
            || $this->serverNotification instanceof AppStoreV2ServerNotification
        );

        if ($this->serverNotification instanceof AppStoreServerNotification) {
            return $this->serverNotification->getAutoRenewStatusChangedAt();
        }

        return null;
    }

    /**
     * @deprecated Use {@see self::getAutoRenewStatusChangedAt()} instead. Will be removed in the next major release.
     *
     * @psalm-suppress DeprecatedClass Retained for backward compatibility until the next major release.
     * @psalm-suppress DeprecatedMethod Delegates to the legacy accessor to preserve compatibility.
     */
    public function getAutoRenewStatusChangeDate(): ?Time
    {
        assert(
            $this->serverNotification instanceof AppStoreServerNotification
            || $this->serverNotification instanceof AppStoreV2ServerNotification
        );

        if ($this->serverNotification instanceof AppStoreServerNotification) {
            return $this->serverNotification->getAutoRenewStatusChangeDate();
        }

        return null;
    }
}
