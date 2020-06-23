<?php


namespace Imdhemy\Purchases\Exceptions;

use Exception;

/**
 * Class CouldNotCreateGoogleClient
 * @package Imdhemy\Purchases\Exceptions
 */
class CouldNotCreateGoogleClient extends Exception
{
    /**
     * @return static
     */
    public static function credentialsNotFound(): self
    {
        $msg = "Google app credentials not found in the specified location";

        return new self($msg);
    }
}
