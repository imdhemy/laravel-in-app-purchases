<?php

declare(strict_types=1);

namespace Imdhemy\Purchases\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Str;
use Imdhemy\Purchases\Contracts\UrlGenerator;

/**
 * A command to generate signed url to the server notification handler endpoint.
 */
final class LiapUrlCommand extends Command
{
    private const CHOICE_ALL = 'All Providers';
    private const CHOICES = [
        self::CHOICE_ALL,
        'App Store',
        'Google Play',
    ];

    protected $signature = 'liap:url';
    protected $description = 'Generates a signed URL to the server notifications handler endpoint';

    private UrlGenerator $urlGenerator;

    public function __construct(UrlGenerator $urlGenerator)
    {
        parent::__construct();

        $this->urlGenerator = $urlGenerator;
    }

    public function handle(): int
    {
        $choices = $this->choices();

        foreach ($choices as $choice) {
            $url = $this->generateUrlOf($choice);
            $this->info(sprintf('%s: %s', $choice, $url));
        }

        return self::SUCCESS;
    }

    /**
     * @return string[]
     */
    private function choices(): array
    {
        $choice = $this->choice('Select provider', self::CHOICES);
        assert(is_string($choice));

        $result = array_slice(self::CHOICES, 1);
        if (self::CHOICE_ALL !== $choice) {
            $result = [$choice];
        }

        return $result;
    }

    private function generateUrlOf(string $provider): string
    {
        $providerSlug = (string)Str::of($provider)->slug();

        return $this->shouldSign()
            ? $this->urlGenerator->signedUrl($providerSlug)
            : $this->urlGenerator->unsignedUrl($providerSlug);
    }

    private function shouldSign(): bool
    {
        return (bool)config('liap.routing.signed');
    }
}
