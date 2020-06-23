<?php


namespace Imdhemy\Purchases\Exceptions;

use Exception;

/**
 * Class CouldNotCreateSubscription
 * @package Imdhemy\Purchases\Exceptions
 */
class CouldNotCreateSubscription extends Exception
{
    /**
     * @return static
     */
    public static function googlePlayPackageNotSet(): self
    {
        $msg = "Google play package is required. check the configuration file.";

        return new self($msg);
    }
}
