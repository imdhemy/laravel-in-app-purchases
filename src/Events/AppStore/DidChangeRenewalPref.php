<?php

namespace Imdhemy\Purchases\Events\AppStore;

use Imdhemy\Purchases\Events\PurchaseEvent;

class DidChangeRenewalPref extends PurchaseEvent
{
    /**
     * @return string|null
     */
    public function getAutoRenewProductId(): ?string
    {
        return $this->serverNotification->getAutoRenewProductId();
    }
}
