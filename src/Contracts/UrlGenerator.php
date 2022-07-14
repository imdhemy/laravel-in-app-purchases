<?php

namespace Imdhemy\Purchases\Contracts;

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
}
