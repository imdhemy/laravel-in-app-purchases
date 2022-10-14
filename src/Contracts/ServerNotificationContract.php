<?php

namespace Imdhemy\Purchases\Contracts;

use GuzzleHttp\Client;

/**
 * Interface ServerNotificationContract
 *
 * @package Imdhemy\Purchases\Events\Contracts
 */
interface ServerNotificationContract
{
    /**
     * Gets the notification type
     *
     * @return string
     */
    public function getType(): string;

    /**
     * Gets the subscription associated with the notification
     *
     * @param Client|null $client
     *
     * @return SubscriptionContract
     */
    public function getSubscription(?Client $client = null): SubscriptionContract;

    /**
     * Returns true if the notification is a test notification
     *
     * @return bool
     */
    public function isTest(): bool;

    /**
     * Gets the application bundle
     *
     * @return string
     */
    public function getBundle(): string;

    /**
     * Gets the notification payload
     *
     * @return array
     */
    public function getPayload(): array;
}
