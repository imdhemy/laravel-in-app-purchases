<?php


namespace Imdhemy\Purchases\Events\AppStore;

use Imdhemy\Purchases\Events\PurchaseEvent;
use Imdhemy\Purchases\ValueObjects\Time;

class DidChangeRenewalStatus extends PurchaseEvent
{
    /**
     * @return bool
     */
    public function isAutoRenewal(): bool
    {
        return $this->serverNotification->isAutoRenewal();
    }

    /**
     * @return Time|null
     */
    public function getAutoRenewStatusChangeDate(): ?Time
    {
        return $this->serverNotification->getAutoRenewStatusChangeDate();
    }
}
