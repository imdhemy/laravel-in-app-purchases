<?php

declare(strict_types=1);

namespace Imdhemy\Purchases\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Imdhemy\Purchases\Contracts\UrlGenerator;

/**
 * A command to generate signed url to the server notification handler endpoint.
 */
class LiapUrlCommand extends Command
{
    public const CHOICE_PROVIDER = 'Select provider';

    public const CONFIRM_GENERATE_SIGNED_ROUTES = 'Signed routes are disabled. Do you want to generate signed routes?';

    public const PROVIDER_ALL = 'All Providers';

    public const PROVIDER_APP_STORE = 'App Store';

    public const PROVIDER_GOOGLE_PLAY = 'Google Play';

    public const TABLE_HEADERS = ['Provider', 'URL'];

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'liap:url';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generates a signed URL to the server notifications handler endpoint';

    private UrlGenerator $urlGenerator;

    private Collection $urlCollection;

    /**
     * Execute the console command.
     */
    public function handle(UrlGenerator $urlGenerator, Collection $urlCollection): int
    {
        $this->urlGenerator = $urlGenerator;

        $this->urlCollection = $urlCollection;

        $this->generateUrls();

        $this->table(self::TABLE_HEADERS, $this->urlCollection->toArray());

        return self::SUCCESS;
    }

    /**
     * Get selected providers.
     *
     * @return string[] List of selected providers
     */
    protected function getProviders(): array
    {
        $provider = $this->choice(self::CHOICE_PROVIDER, [
            self::PROVIDER_ALL,
            self::PROVIDER_APP_STORE,
            self::PROVIDER_GOOGLE_PLAY,
        ]);
        assert(is_string($provider));

        if (self::PROVIDER_ALL === $provider) {
            return [self::PROVIDER_APP_STORE, self::PROVIDER_GOOGLE_PLAY];
        }

        return [$provider];
    }

    /**
     * Generates signed URLs for the submitted providers.
     *
     * @param string[] $providers List of providers
     */
    private function generateSignedUrls(array $providers): void
    {
        foreach ($providers as $provider) {
            $providerSlug = (string)Str::of($provider)->slug();
            $url = $this->urlGenerator->generate($providerSlug);
            $this->urlCollection->add([$provider, $url]);
        }
    }

    /**
     * Generates unsigned URLs for the submitted providers.
     *
     * @param string[] $providers List of providers
     */
    private function generateUnsignedUrls(array $providers): void
    {
        foreach ($providers as $provider) {
            $providerSlug = (string)Str::of($provider)->slug();
            $url = route('liap.serverNotifications').'?provider='.$providerSlug;
            $this->urlCollection->add([$provider, $url]);
        }
    }

    /**
     * Checks if the user wants to generate signed URLs.
     */
    protected function shouldGenerateSignedUrls(): bool
    {
        return
            config('liap.routing.signed')
            || $this->confirm(self::CONFIRM_GENERATE_SIGNED_ROUTES);
    }

    protected function generateUrls(): void
    {
        $providers = $this->getProviders();

        if ($this->shouldGenerateSignedUrls()) {
            $this->generateSignedUrls($providers);

            return;
        }

        $this->generateUnsignedUrls($providers);
    }
}
