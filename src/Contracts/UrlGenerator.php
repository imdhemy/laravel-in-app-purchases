<?php

namespace Imdhemy\Purchases\Contracts;

use Illuminate\Http\Request;

/**
 * URL Generator interface
 */
interface UrlGenerator
{
    /**
     * Generates URL to the server notification handler
     *
     * @param string $provider
     *
     * @return string
     */
    public function generate(string $provider): string;

    /**
     * Determine if the given request has a valid signature.
     *
     * @param Request $request
     *
     * @return bool
     */
    public function hasValidSignature(Request $request): bool;
}
