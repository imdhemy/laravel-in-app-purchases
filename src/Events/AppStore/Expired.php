<?php

declare(strict_types=1);

namespace Imdhemy\Purchases\Events\AppStore;

use Imdhemy\Purchases\Events\PurchaseEvent;
use Imdhemy\Purchases\ServerNotifications\AppStoreV2ServerNotification;

/**
 * @method AppStoreV2ServerNotification getServerNotification()
 */
class Expired extends PurchaseEvent
{
}
