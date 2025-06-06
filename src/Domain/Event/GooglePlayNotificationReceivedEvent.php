<?php

declare(strict_types=1);

namespace Imdhemy\Purchases\Domain\Event;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Imdhemy\Purchases\Domain\Model\GooglePlay\GooglePlayNotificationPayload;

final readonly class GooglePlayNotificationReceivedEvent
{
    use Dispatchable;
    use SerializesModels;

    public function __construct(public GooglePlayNotificationPayload $payload)
    {
    }
}
