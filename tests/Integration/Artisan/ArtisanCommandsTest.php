<?php

namespace Tests\Integration\Artisan;

use Illuminate\Console\Command;
use Illuminate\Contracts\Console\Kernel;
use Illuminate\Support\Facades\File;
use Imdhemy\Purchases\Console\LiapConfigPublishCommand;
use Tests\TestCase;

/**
 * The word `it` refers to LIAP package
 */
class ArtisanCommandsTest extends TestCase
{
    /**
     * @var array<string, Command>
     */
    private array $commands;

    /**
     * @var string The path to the configuration file `config/liap.php`
     */
    private string $configFilePath;

    /**
     * @inheritDoc
     * - Gets all artisan commands
     * - Deletes the configuration file if it exists
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->commands = $this->app->make(Kernel::class)->all();

        $this->configFilePath = config_path('liap.php');
        if (File::exists($this->configFilePath)) {
            unlink($this->configFilePath);
        }
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
     * @depends it_should_provide_publish_config_command
     */
    public function publish_config_command_should_publish_the_config_file(): void
    {
        $this->artisan('liap:config:publish')
            ->expectsOutput(LiapConfigPublishCommand::MESSAGE_SUCCESS)
            ->assertExitCode(0);

        $this->assertFileExists($this->configFilePath);
    }

    /**
     * @test
     */
    public function it_should_provide_url_command(): void
    {
        $this->assertArrayHasKey('liap:url', $this->commands);
    }
}
