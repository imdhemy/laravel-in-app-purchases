<?php

declare(strict_types=1);

namespace Imdhemy\Purchases\Contracts;

/**
 * Interface PurchaseEventContract.
 *
 * @psalm-api
 */
interface PurchaseEventContract
{
    public function getServerNotification(): ServerNotificationContract;
}
