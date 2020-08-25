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
    public static function purchaseNotUnique(): self
    {
        $msg = "Could not persist the purchase receipt. The specified receipt is not unique.";

        return new self($msg);
    }
}
