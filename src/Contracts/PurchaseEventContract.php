<?php

declare(strict_types=1);

namespace Imdhemy\Purchases\Contracts;

/**
 * Interface PurchaseEventContract.
 */
interface PurchaseEventContract
{
    public function getServerNotification(): ServerNotificationContract;
}
