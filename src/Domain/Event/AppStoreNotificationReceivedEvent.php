<?php

declare(strict_types=1);

namespace Imdhemy\Purchases\Domain\Event;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

final class AppStoreNotificationReceivedEvent
{
    use Dispatchable;
    use SerializesModels;
}
