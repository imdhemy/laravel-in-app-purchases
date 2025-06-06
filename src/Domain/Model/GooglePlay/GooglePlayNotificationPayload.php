<?php

declare(strict_types=1);

namespace Imdhemy\Purchases\Domain\Model\GooglePlay;

final readonly class GooglePlayNotificationPayload
{
    public function __construct(public Message $message)
    {
    }
}
