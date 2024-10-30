<?php

declare(strict_types=1);

namespace Imdhemy\Purchases\Tests\Console;

use Illuminate\Support\Str;
use Illuminate\Testing\PendingCommand;
use Imdhemy\Purchases\Contracts\UrlGenerator as UrlGeneratorContract;
use Imdhemy\Purchases\Tests\Doubles\UrlGenerator as FakeUrlGenerator;
use Imdhemy\Purchases\Tests\TestCase;

final class LiapUrlCommandTest extends TestCase
{
    private UrlGeneratorContract $urlGenerator;

    protected function setUp(): void
    {
        parent::setUp();

        config()->set('liap.routing.signed', true);

        $this->urlGenerator = $this->app->make(FakeUrlGenerator::class);
    }

    /** @test */
    public function generate_a_signed_url(): void
    {
        $provider = (string)$this->faker->randomElement([
            'App Store',
            'Google Play',
        ]);

        $this->runWithChoice($provider)
            ->expectsOutput(sprintf('%s: %s', $provider, $this->signedUrlOf($provider)))
            ->assertSuccessful();
    }

    /** @test */
    public function generate_singed_url_for_all_providers(): void
    {
        $this->runWithChoice()
            ->expectsOutput(sprintf('%s: %s', 'App Store', $this->signedUrlOf('App Store')))
            ->expectsOutput(sprintf('%s: %s', 'Google Play', $this->signedUrlOf('Google Play')))
            ->assertSuccessful();
    }

    /** @test */
    public function it_should_sign_urls_only_if_config_is_enabled(): void
    {
        config()->set('liap.routing.signed', false);

        $this->runWithChoice()
            ->expectsOutput(sprintf('%s: %s', 'App Store', $this->unsignedUrlOf('App Store')))
            ->expectsOutput(sprintf('%s: %s', 'Google Play', $this->unsignedUrlOf('Google Play')))
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

    private function signedUrlOf(string $provider): string
    {
        return $this->urlGenerator->signedUrl((string)Str::of($provider)->slug());
    }

    private function unsignedUrlOf(string $provider): string
    {
        return route('liap.serverNotifications').'?provider='.Str::of($provider)->slug();
    }
}
