<?php

namespace Imdhemy\Purchases\Tests\Integration\Artisan;

use Illuminate\Console\Command;
use Illuminate\Contracts\Console\Kernel;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Imdhemy\Purchases\Console\LiapConfigPublishCommand;
use Imdhemy\Purchases\Console\LiapUrlCommand;
use Imdhemy\Purchases\Tests\Doubles\UrlGeneratorDouble;
use Imdhemy\Purchases\Tests\TestCase;

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
     * - Enables signed URLs
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->commands = $this->app->make(Kernel::class)->all();

        $this->configFilePath = config_path('liap.php');
        if (File::exists($this->configFilePath)) {
            unlink($this->configFilePath);
        }

        config()->set('liap.routing.signed', true);
    }

    /**
     * @test
     */
    public function it_should_provide_config_publish_command(): void
    {
        $this->assertArrayHasKey('liap:config:publish', $this->commands);
    }

    /**
     * @test
     * @depends it_should_provide_config_publish_command
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

    /**
     * @test
     * @depends it_should_provide_url_command
     */
    public function url_command_prints_table_of_provider_endpoints(): void
    {
        $answers = [
            LiapUrlCommand::PROVIDER_ALL,
            LiapUrlCommand::PROVIDER_APP_STORE,
            LiapUrlCommand::PROVIDER_GOOGLE_PLAY,
        ];

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

        $this->artisan('liap:url')
            ->expectsChoice(
                LiapUrlCommand::CHOICE_PROVIDER,
                LiapUrlCommand::PROVIDER_ALL,
                $answers
            )->expectsTable(LiapUrlCommand::TABLE_HEADERS, $expected->toArray())
            ->assertSuccessful();
    }
}
