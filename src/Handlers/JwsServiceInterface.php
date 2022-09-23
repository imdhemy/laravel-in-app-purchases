<?php

namespace Imdhemy\Purchases\Handlers;

use Imdhemy\AppStore\Jws\JsonWebSignature;

interface JwsServiceInterface
{
    /**
     * Verify the JWS
     *
     * @return bool
     */
    public function verify(): bool;

    /**
     * Parses the string into a JWS
     *
     * @return JsonWebSignature
     */
    public function parse(): JsonWebSignature;
}
