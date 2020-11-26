<?php


namespace Imdhemy\Purchases\Contracts;

use Imdhemy\Purchases\ValueObjects\Time;

/**
 * Interface SubscriptionContract
 * @package Imdhemy\Purchases\Events\Contracts
 */
interface SubscriptionContract
{
    /**
     * @return Time
     */
    public function getExpiryTime(): Time;

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
