<?php

namespace Imdhemy\Purchases\Events\AppStore;

use Imdhemy\AppStore\ServerNotifications\V2DecodedPayload;
use Imdhemy\Purchases\Events\PurchaseEvent;
use Imdhemy\Purchases\ServerNotifications\AppStoreServerNotification;
use Imdhemy\Purchases\ServerNotifications\AppStoreV2ServerNotification;

class DidChangeRenewalPref extends PurchaseEvent
{
    /**
     * @return string|null
     */
    public function getAutoRenewProductId(): ?string
    {
        assert(
            $this->serverNotification instanceof AppStoreServerNotification ||
            $this->serverNotification instanceof AppStoreV2ServerNotification
        );

        if ($this->serverNotification instanceof AppStoreServerNotification) {
            return $this->serverNotification->getAutoRenewProductId();
        }

        $payload = V2DecodedPayload::fromArray($this->serverNotification->getPayload());

        return $payload->getRenewalInfo()->getAutoRenewProductId();
    }
}
