<?php

declare(strict_types=1);

namespace Imdhemy\Purchases\Events\AppStore;

use Imdhemy\Purchases\Events\PurchaseEvent;

class DidChangeRenewalPref extends PurchaseEvent
{
    public function getAutoRenewProductId(): ?string
    {
        return $this->serverNotification->getAutoRenewProductId();
    }
}
