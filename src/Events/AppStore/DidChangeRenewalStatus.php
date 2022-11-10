<?php

declare(strict_types=1);

namespace Imdhemy\Purchases\Events\AppStore;

use Imdhemy\Purchases\Events\PurchaseEvent;
use Imdhemy\Purchases\ValueObjects\Time;

class DidChangeRenewalStatus extends PurchaseEvent
{
    public function isAutoRenewal(): bool
    {
        return $this->serverNotification->isAutoRenewal();
    }

    public function getAutoRenewStatusChangeDate(): ?Time
    {
        return $this->serverNotification->getAutoRenewStatusChangeDate();
    }
}
