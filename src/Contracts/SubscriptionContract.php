<?php


namespace Imdhemy\Purchases\Contracts;

use Carbon\Carbon;

/**
 * Interface SubscriptionContract
 * @package Imdhemy\Purchases\Events\Contracts
 */
interface SubscriptionContract
{
    /**
     * @return Carbon
     */
    public function getExpiryTime(): Carbon;

    /**
     * @return string
     */
    public function getItemId(): string;

    /**
     * @return string
     */
    public function getProvider(): string;

    /**
     * @return string
     */
    public function getUniqueIdentifier(): string;

    /**
     * @return mixed
     */
    public function getProviderRepresentation();
}
