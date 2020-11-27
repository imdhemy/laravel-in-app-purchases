<?php


namespace Imdhemy\Purchases\Contracts;

/**
 * Interface PurchaseEventContract
 * @package Imdhemy\Purchases\Events\Contracts
 */
interface PurchaseEventContract
{
    /**
     * @return ServerNotificationContract
     */
    public function getServerNotification(): ServerNotificationContract;
}
