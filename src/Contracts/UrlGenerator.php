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
     * @deprecated use signedUrl() instead
     */
    public function generate(string $provider): string;

    /**
     * Generate a signed URL for the given provider.
     */
    public function signedUrl(string $provider): string;

    /**
     * Generate an unsigned URL for the given provider.
     */
    public function unsignedUrl(string $provider): string;

    /**
     * Determine if the given request has a valid signature.
     */
    public function hasValidSignature(Request $request): bool;
}
