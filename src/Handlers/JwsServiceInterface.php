<?php

declare(strict_types=1);

namespace Imdhemy\Purchases\Handlers;

use Imdhemy\AppStore\Jws\JsonWebSignature;

interface JwsServiceInterface
{
    /**
     * Verify the JWS.
     */
    public function verify(): bool;

    /**
     * Parses the string into a JWS.
     */
    public function parse(): JsonWebSignature;
}
