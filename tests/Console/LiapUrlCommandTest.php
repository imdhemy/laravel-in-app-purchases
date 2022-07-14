<?php

namespace Tests\Console;

use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Illuminate\Testing\PendingCommand;
use Imdhemy\Purchases\Console\LiapUrlCommand;
use Tests\Doubles\UrlGeneratorDouble;
use Tests\TestCase;

class LiapUrlCommandTest extends TestCase
{
    use WithFaker;

    public const CHOICES = [
      LiapUrlCommand::PROVIDER_ALL,
      LiapUrlCommand::PROVIDER_APP_STORE,
      LiapUrlCommand::PROVIDER_GOOGLE_PLAY,
    ];

    /**
     * @test
     */
    public function signature()
    {
        $this->runWithChoice()->assertSuccessful();
    }

    /**
     * @param string $choice
     *
     * @return PendingCommand
     */
    private function runWithChoice(string $choice = LiapUrlCommand::PROVIDER_ALL): PendingCommand
    {
        return
          $this->artisan('liap:url')
            ->expectsChoice(LiapUrlCommand::CHOICE_PROVIDER, $choice, self::CHOICES);
    }

    /**
     * @test
     */
    public function it_should_ask_for_the_provider()
    {
        $this->runWithChoice()->assertSuccessful();
    }

    /**
     * @test
     */
    public function it_can_generate_a_signed_url_for_a_single_provider()
    {
        /** @var UrlGeneratorDouble $urlGenerator */
        $urlGenerator = $this->app->make(UrlGeneratorDouble::class);
        $provider = $this->faker->randomElement([
          LiapUrlCommand::PROVIDER_APP_STORE,
          LiapUrlCommand::PROVIDER_GOOGLE_PLAY,
        ]);
        $providerSlug = (string)Str::of($provider)->slug();

        $expected = [
          [
            $provider,
            $urlGenerator->generate($providerSlug),
          ],
        ];

        $this->runWithChoice($provider)
          ->expectsTable(LiapUrlCommand::TABLE_HEADERS, $expected)
          ->assertSuccessful();
    }

    /**
     * @test
     */
    public function it_can_generate_signed_routes_for_all_providers()
    {
        /** @var UrlGeneratorDouble $urlGenerator */
        $urlGenerator = $this->app->make(UrlGeneratorDouble::class);
        $providers = [LiapUrlCommand::PROVIDER_APP_STORE, LiapUrlCommand::PROVIDER_GOOGLE_PLAY];
        $expected = new Collection();

        foreach ($providers as $provider) {
            $expected->add([
              $provider,
              $urlGenerator->generate((string)Str::of($provider)->slug()),
            ]);
        }

        $this->runWithChoice()
          ->expectsTable(LiapUrlCommand::TABLE_HEADERS, $expected->toArray())
          ->assertSuccessful();
    }
}
