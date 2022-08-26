<?php

namespace Tests\Integration\Artisan;

use Illuminate\Console\Command;
use Illuminate\Contracts\Console\Kernel;
use Tests\TestCase;

class ArtisanCommandsTest extends TestCase
{
    /**
     * @var array<string, Command>
     */
    private $commands;

    /**
     * @inheritDoc
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->commands = $this->app->make(Kernel::class)->all();
    }

    /**
     * @test
     */
    public function it_should_provide_publish_config_command(): void
    {
        $this->assertArrayHasKey('liap:config:publish', $this->commands);
    }

    /**
     * @test
     */
    public function it_should_provide_url_command(): void
    {
        $this->assertArrayHasKey('liap:url', $this->commands);
    }
}
