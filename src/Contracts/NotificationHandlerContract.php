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
     */
    public function execute(): void;
}
