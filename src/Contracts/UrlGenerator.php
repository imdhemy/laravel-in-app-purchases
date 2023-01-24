<?php

declare(strict_types=1);

namespace Imdhemy\Purchases\Contracts;

use Illuminate\Http\Request;

/**
 * URL Generator interface.
 */
interface UrlGenerator
{
    /**
     * Generates URL to the server notification handler.
     */
    public function generate(string $provider): string;

    /**
     * Determine if the given request has a valid signature.
     */
    public function hasValidSignature(Request $request): bool;
}
