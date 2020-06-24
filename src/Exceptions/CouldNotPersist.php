<?php


namespace Imdhemy\Purchases\Exceptions;

use Exception;

/**
 * Class CouldNotPersist
 * @package Imdhemy\Purchases\Exceptions
 */
class CouldNotPersist extends Exception
{
    /**
     * @return static
     */
    public static function suscriptionPurchaseNotUnique(): self
    {
        $msg = "Could not persist the subscription purchase. The specified receipt is not unique.";

        return new self($msg);
    }
}
