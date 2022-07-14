<?php

namespace Tests\Console;

use Illuminate\Testing\PendingCommand;
use Imdhemy\Purchases\Console\LiapUrlCommand;
use Tests\Doubles\UrlGeneratorDouble;
use Tests\TestCase;

class LiapUrlCommandTest extends TestCase
{
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
    public function it_should_generate_a_signed_url_for_app_store()
    {
        $urlGenerator = $this->app->make(UrlGeneratorDouble::class);

        $expected = [
          [
            LiapUrlCommand::PROVIDER_APP_STORE,
            $urlGenerator->generate('app-store'),
          ],
        ];

        $this->runWithChoice(LiapUrlCommand::PROVIDER_APP_STORE)
          ->expectsTable(LiapUrlCommand::TABLE_HEADERS, $expected)
          ->assertSuccessful();
    }
}
