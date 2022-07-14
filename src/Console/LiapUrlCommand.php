<?php

namespace Imdhemy\Purchases\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Imdhemy\Purchases\Contracts\UrlGenerator;

/**
 * A command to generate signed url to the server notification handler endpoint
 */
class LiapUrlCommand extends Command
{
    public const CHOICE_PROVIDER = 'Select provider';

    public const PROVIDER_ALL = 'All Providers';

    public const PROVIDER_APP_STORE = 'App Store';

    public const PROVIDER_GOOGLE_PLAY = 'Google Play';

    public const TABLE_HEADERS = ['Provider', 'URL'];

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = "liap:url";

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "Generates a signed URL to the server notifications handler endpoint";

    /**
     * @var UrlGenerator
     */
    private $urlGenerator;

    /**
     * @var Collection
     */
    private $urlCollection;

    /**
     * @inheritDoc
     */
    public function __construct(UrlGenerator $urlGenerator, Collection $urlCollection)
    {
        parent::__construct();

        $this->urlGenerator = $urlGenerator;

        $this->urlCollection = $urlCollection;
    }


    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(): int
    {
        $providers = $this->getProviders();

        foreach ($providers as $provider) {
            $this->appendUrl($provider);
        }

        $this->table(self::TABLE_HEADERS, $this->urlCollection->toArray());

        return self::SUCCESS;
    }

    /**
     * Get selected providers
     *
     * @return array
     */
    protected function getProviders(): array
    {
        $provider = $this->choice(self::CHOICE_PROVIDER, [
          self::PROVIDER_ALL,
          self::PROVIDER_APP_STORE,
          self::PROVIDER_GOOGLE_PLAY,
        ]);

        if ($provider === self::PROVIDER_ALL) {
            return [self::PROVIDER_APP_STORE, self::PROVIDER_GOOGLE_PLAY];
        }

        return [$provider];
    }

    /**
     * Appends a signed URL for the submitted provider
     *
     * @param string $provider
     *
     * @return void
     */
    private function appendUrl(string $provider): void
    {
        $providerSlug = (string)Str::of($provider)->slug();
        $signedRoute = $this->urlGenerator->generate($providerSlug);
        $this->urlCollection->add([$provider, $signedRoute]);
    }
}
