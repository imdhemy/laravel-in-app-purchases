<?php

namespace Tests\Console;

use Imdhemy\Purchases\Console\LiapUrlCommand;
use Tests\TestCase;

class LiapUrlCommandTest extends TestCase
{
    /**
     * @test
     */
    public function signature()
    {
        $this->artisan('liap:url')->assertSuccessful();
    }

    /**
     * @test
     */
    public function it_should_ask_for_the_provider()
    {
        $this->artisan('liap:url')
          ->expectsChoice(
              LiapUrlCommand::CHOICE_PROVIDER,
              LiapUrlCommand::PROVIDER_APP_STORE,
              [
              LiapUrlCommand::PROVIDER_APP_STORE,
              LiapUrlCommand::PROVIDER_GOOGLE_PLAY,
              LiapUrlCommand::PROVIDER_ALL,
            ]
          )->assertSuccessful();
    }
}
