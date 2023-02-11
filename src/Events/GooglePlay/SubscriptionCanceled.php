<?php

declare(strict_types=1);

namespace Imdhemy\Purchases\Events\GooglePlay;

use Imdhemy\Purchases\Events\PurchaseEvent;
use Imdhemy\Purchases\ServerNotifications\GoogleServerNotification;

/**
 * @method GoogleServerNotification getServerNotification()
 */
class SubscriptionCanceled extends PurchaseEvent
{
}
