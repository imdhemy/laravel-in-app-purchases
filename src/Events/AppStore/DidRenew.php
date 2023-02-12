<?php

declare(strict_types=1);

namespace Imdhemy\Purchases\Events\AppStore;

use Imdhemy\Purchases\Events\PurchaseEvent;
use Imdhemy\Purchases\ServerNotifications\AppStoreServerNotification;
use Imdhemy\Purchases\ServerNotifications\AppStoreV2ServerNotification;

/**
 * @method AppStoreServerNotification|AppStoreV2ServerNotification getServerNotification()
 */
class DidRenew extends PurchaseEvent
{
}
