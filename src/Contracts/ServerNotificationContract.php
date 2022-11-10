<?php

declare(strict_types=1);

namespace Imdhemy\Purchases\Contracts;

use GuzzleHttp\Client;

/**
 * Interface ServerNotificationContract.
 */
interface ServerNotificationContract
{
    /**
     * Gets the notification type.
     */
    public function getType(): string;

    /**
     * Gets the subscription associated with the notification.
     */
    public function getSubscription(?Client $client = null): SubscriptionContract;

    /**
     * Returns true if the notification is a test notification.
     */
    public function isTest(): bool;

    /**
     * Gets the application bundle.
     */
    public function getBundle(): string;

    /**
     * Gets the notification payload.
     */
    public function getPayload(): array;
}
