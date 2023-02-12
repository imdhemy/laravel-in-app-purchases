<?php

declare(strict_types=1);

namespace Imdhemy\Purchases\Events\AppStore;

use Imdhemy\Purchases\Events\PurchaseEvent;
use Imdhemy\Purchases\ServerNotifications\AppStoreServerNotification;

/**
 * @deprecated use \Imdhemy\Purchases\Events\AppStore\DidRecover instead
 * @see        DidRecover
 *
 * @method AppStoreServerNotification getServerNotification()
 */
class Renewal extends PurchaseEvent
{
}
