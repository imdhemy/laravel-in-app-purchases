<?php


namespace Imdhemy\Purchases\Contracts;

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
     * @param array $jsonKey
     * @return SubscriptionContract
     */
    public function getSubscription(array $jsonKey = []): SubscriptionContract;

    /**
     * @return bool
     */
    public function isTest(): bool;

    /**
     * @return string
     */
    public function getBundle(): string;
}
