<?php

declare(strict_types=1);

namespace Imdhemy\Purchases\Console;

use Illuminate\Http\Request;
use Illuminate\Routing\UrlGenerator as LaravelUrlGenerator;
use Illuminate\Support\Str;
use Imdhemy\Purchases\Contracts\UrlGenerator as UrlGeneratorContract;

/**
 * A helper class to generate a signed URL to the server notification handler.
 */
class UrlGenerator implements UrlGeneratorContract
{
    private LaravelUrlGenerator $urlGenerator;

    /**
     * Creates an Url generator instance.
     */
    public function __construct(LaravelUrlGenerator $urlGenerator)
    {
        $this->urlGenerator = $urlGenerator;
    }

    public function generate(string $provider): string
    {
        $singedUrl = $this->urlGenerator->signedRoute('liap.serverNotifications');

        return sprintf('%s&provider=%s', $singedUrl, $provider);
    }

    /**
     * This method returns true if and only if the given URL is a valid signed URL
     * It's used to provide the same functionality as the Laravel UrlGenerator v.9
     * which allows to ignore specific query parameters when validating the URL.
     *
     * {@inheritDoc}
     */
    public function hasValidSignature(Request $request): bool
    {
        if ($this->shouldDelegateToLaravel()) {
            return $this->validateByLaravel($request);
        }

        $ignoreQuery = ['signature', 'provider'];
        $url = $request->url();

        $queryString = collect(explode('&', (string)$request->server->get('QUERY_STRING')))
            ->reject(function (string $parameter) use ($ignoreQuery) {
                return in_array(Str::before($parameter, '='), $ignoreQuery);
            })
            ->join('&');

        $original = rtrim($url.'?'.$queryString, '?');
        $signature = hash_hmac('sha256', $original, (string)config('app.key'));

        /** @var string $signatureQuery */
        $signatureQuery = $request->query('signature', '');

        return hash_equals($signature, $signatureQuery);
    }

    protected function shouldDelegateToLaravel(): bool
    {
        return version_compare(app()->version(), '9', '>=');
    }

    /**
     * This method is only used when Laravel is >= 9.0.0
     * Starting from Laravel 9.0.0, the `hasValidSignature` method allows to
     * pass query names to be ignored when validating the signature.
     *
     * @psalm-suppress TooManyArguments
     */
    protected function validateByLaravel(Request $request): bool
    {
        return $this->urlGenerator->hasValidSignature($request, true, ['provider']);
    }
}
