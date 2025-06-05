<?php

declare(strict_types=1);

namespace Imdhemy\Purchases\Console;

use Illuminate\Http\Request;
use Illuminate\Routing\UrlGenerator as LaravelUrlGenerator;
use Imdhemy\Purchases\Contracts\UrlGenerator as UrlGeneratorContract;

/**
 * A helper class to generate a signed URL to the server notification handler.
 */
class UrlGenerator implements UrlGeneratorContract
{
    private LaravelUrlGenerator $urlGenerator;

    public function __construct(LaravelUrlGenerator $urlGenerator)
    {
        $this->urlGenerator = $urlGenerator;
    }

    public function signedUrl(string $provider): string
    {
        $singedUrl = $this->urlGenerator->signedRoute('liap.serverNotifications');

        return sprintf('%s&provider=%s', $singedUrl, $provider);
    }

    public function unsignedUrl(string $provider): string
    {
        $url = $this->urlGenerator->route('liap.serverNotifications');

        return sprintf('%s?provider=%s', $url, $provider);
    }

    public function generate(string $provider): string
    {
        return $this->signedUrl($provider);
    }

    public function hasValidSignature(Request $request): bool
    {
        return $this->urlGenerator->hasValidSignature($request, true, ['provider']);
    }
}
