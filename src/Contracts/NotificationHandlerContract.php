<?php

declare(strict_types=1);

namespace Imdhemy\Purchases\Contracts;

/**
 * Notification handler.
 *
 * All server notification handlers MUST implement this contract
 */
interface NotificationHandlerContract
{
    /**
     * Executes the handler.
     *
     * @psalm-suppress MissingReturnType - @todo add return type
     */
    public function execute();
}
