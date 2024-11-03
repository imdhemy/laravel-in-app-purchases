<?php

declare(strict_types=1);

namespace Imdhemy\Purchases\Tests\Console;

use Illuminate\Support\Str;
use Illuminate\Testing\PendingCommand;
use Imdhemy\Purchases\Contracts\UrlGenerator as UrlGeneratorContract;
use Imdhemy\Purchases\Tests\TestCase;

final class LiapUrlCommandTest extends TestCase
{
    private UrlGeneratorContract $urlGenerator;

    protected function setUp(): void
    {
        parent::setUp();

        $urlGenerator = $this->app->make(UrlGeneratorContract::class);
        assert($urlGenerator instanceof UrlGeneratorContract);

        $this->urlGenerator = $urlGenerator;
    }

    /** @test */
    public function generate_a_signed_url(): void
    {
        config()->set('liap.routing.signed', true);
        $provider = (string)$this->faker->randomElement([
            'App Store',
            'Google Play',
        ]);
        $slug = Str::slug($provider);

        $this->runWithChoice($provider)
            ->expectsOutput(sprintf('%s: %s', $provider, $this->urlGenerator->signedUrl($slug)))
            ->assertSuccessful();
    }

    /** @test */
    public function generate_singed_url_for_all_providers(): void
    {
        config()->set('liap.routing.signed', true);
        $this->runWithChoice()
            ->expectsOutput(sprintf('%s: %s', 'App Store', $this->urlGenerator->signedUrl('app-store')))
            ->expectsOutput(sprintf('%s: %s', 'Google Play', $this->urlGenerator->signedUrl('google-play')))
            ->assertSuccessful();
    }

    /** @test */
    public function it_should_sign_urls_only_if_config_is_enabled(): void
    {
        config()->set('liap.routing.signed', false);
        $this->runWithChoice()
            ->expectsOutput(sprintf('%s: %s', 'App Store', $this->urlGenerator->unsignedUrl('app-store')))
            ->expectsOutput(sprintf('%s: %s', 'Google Play', $this->urlGenerator->unsignedUrl('google-play')))
            ->assertSuccessful();
    }

    private function runWithChoice(string $choice = 'All Providers'): PendingCommand
    {
        return
            $this->artisan('liap:url')
                ->expectsChoice('Select provider', $choice, [
                    'All Providers',
                    'App Store',
                    'Google Play',
                ]);
    }
}
