<?php

namespace Imdhemy\Purchases\Console;

use Illuminate\Http\Request;
use Illuminate\Routing\UrlGenerator as LaravelUrlGenerator;
use Illuminate\Support\Str;
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

    /**
     * @inheritDoc
     * @psalm-suppress RedundantCastGivenDocblockType
     * @psalm-suppress TooManyArguments
     */
    public function hasValidSignature(Request $request): bool
    {
        if (version_compare(app()->version(), '9', '>=')) {
            return (bool)$this->urlGenerator->hasValidSignature($request, true, ['provider']);
        }

        $ignoreQuery = ['signature', 'provider'];
        $url = $request->url();

        $queryString = collect(explode('&', (string)$request->server->get('QUERY_STRING')))
          ->reject(function ($parameter) use ($ignoreQuery) {
              return in_array(Str::before($parameter, '='), $ignoreQuery);
          })
          ->join('&');

        $original = rtrim($url . '?' . $queryString, '?');
        $signature = hash_hmac('sha256', $original, config('app.key'));

        return hash_equals($signature, (string)$request->query('signature', ''));
    }
}
