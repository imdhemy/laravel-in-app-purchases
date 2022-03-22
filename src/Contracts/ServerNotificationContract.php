<?php

namespace Imdhemy\Purchases\Contracts;

use GuzzleHttp\Client;

/**
 * Interface ServerNotificationContract
 * @package Imdhemy\Purchases\Events\Contracts
 */
interface ServerNotificationContract
{
    /**
     * @return string
     */
    public function getType(): string;

    /**
     * @param Client|null $client
     * @return SubscriptionContract
     */
    public function getSubscription(?Client $client = null): SubscriptionContract;

    /**
     * @return bool
     */
    public function isTest(): bool;

    /**
     * @return string
     */
    public function getBundle(): string;
}
