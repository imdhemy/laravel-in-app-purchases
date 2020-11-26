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
     * @return SubscriptionContract
     */
    public function getSubscription(): SubscriptionContract;
}
