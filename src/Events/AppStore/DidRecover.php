<?php

declare(strict_types=1);

namespace Imdhemy\Purchases\Events\AppStore;

use Imdhemy\Purchases\Events\PurchaseEvent;
use Imdhemy\Purchases\ServerNotifications\AppStoreServerNotification;

/**
 * @method AppStoreServerNotification getServerNotification()
 */
class DidRecover extends PurchaseEvent
{
}
