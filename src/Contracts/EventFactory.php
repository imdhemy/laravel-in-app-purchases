<?php

declare(strict_types=1);

namespace Imdhemy\Purchases\Contracts;

use Imdhemy\Purchases\Contracts\PurchaseEventContract as PurchaseEvent;
use Imdhemy\Purchases\Contracts\ServerNotificationContract as ServerNotification;

interface EventFactory
{
    public function create(ServerNotification $notification): PurchaseEvent;
}
