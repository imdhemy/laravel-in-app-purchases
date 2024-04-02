<?php

declare(strict_types=1);

namespace Imdhemy\Purchases\Events\AppStore;

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
