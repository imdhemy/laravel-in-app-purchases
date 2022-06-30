<?php

namespace Tests\Console;

use Illuminate\Support\Facades\File;
use Imdhemy\Purchases\Console\LiapConfigPublishCommand;
use Tests\TestCase;

class LiapConfigPublishCommandTest extends TestCase
{
    /**
     * @var string
     */
    private $configFilePath;

    /**
     * @inheritdoc
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->configFilePath = config_path('liap.php');
    }

    /**
     * @test
     */
    public function it_copies_the_configuration_file()
    {
        if (File::exists($this->configFilePath)) {
            unlink($this->configFilePath);
        }

        $this->artisan('liap:config:publish')
          ->expectsOutput(LiapConfigPublishCommand::MESSAGE_SUCCESS)
          ->assertExitCode(0);

        $this->assertFileExists($this->configFilePath);
    }

    /**
     * @test
     */
    public function it_fails_if_the_configuration_file_already_exists()
    {
        if (File::exists($this->configFilePath)) {
            unlink($this->configFilePath);
        }

        $command = 'liap:config:publish';

        $this->artisan($command);

        $this->artisan($command)
          ->expectsOutput(LiapConfigPublishCommand::MESSAGE_ALREADY_INSTALLED)
          ->assertExitCode(1);
    }

    /**
     * @test
     */
    public function it_could_be_forced()
    {
        if (File::exists($this->configFilePath)) {
            unlink($this->configFilePath);
        }

        $command = 'liap:config:publish --force';

        $this->artisan($command);

        $this->artisan($command)
          ->expectsOutput(LiapConfigPublishCommand::MESSAGE_SUCCESS)
          ->assertExitCode(0);
    }
}
