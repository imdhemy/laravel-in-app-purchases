<?php

namespace Imdhemy\Purchases\Console;

use Illuminate\Routing\UrlGenerator as LaravelUrlGenerator;
use Imdhemy\Purchases\Contracts\UrlGenerator as UrlGeneratorContract;

/**
 * A helper class to generate a signed URL to the server notification handler
 */
class UrlGenerator implements UrlGeneratorContract
{
    /**
     * @var LaravelUrlGenerator
     */
    private $urlGenerator;

    /**
     * Creates an Url generator instance
     *
     * @param LaravelUrlGenerator $urlGenerator
     */
    public function __construct(LaravelUrlGenerator $urlGenerator)
    {
        $this->urlGenerator = $urlGenerator;
    }

    /**
     * @inheritDoc
     */
    public function generate(string $provider): string
    {
        $singedUrl = $this->urlGenerator->signedRoute('liap.serverNotifications');

        return sprintf("%s&provider=%s", $singedUrl, $provider);
    }
}
