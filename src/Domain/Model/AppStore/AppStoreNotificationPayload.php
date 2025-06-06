<?php

declare(strict_types=1);

namespace Imdhemy\Purchases\Domain\Model\AppStore;

final readonly class AppStoreNotificationPayload
{
    public function __construct(
        public string $signedPayload,
    ) {
    }
}
