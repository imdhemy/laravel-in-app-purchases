<?php

declare(strict_types=1);

namespace Imdhemy\Purchases\Domain\Model\AppStore;

/**
 * @psalm-api
 */
final readonly class AppStoreNotificationPayload
{
    public function __construct(
        public string $signedPayload,
    ) {
    }
}
