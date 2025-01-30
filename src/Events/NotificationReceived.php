<?php

declare(strict_types=1);

namespace Imdhemy\Purchases\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NotificationReceived
{
    use Dispatchable;
    use SerializesModels;

    /**
     * The notification payload.
     *
     * @var array
     */
    public $payload;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(array $payload)
    {
        $this->payload = $payload;
    }
}
