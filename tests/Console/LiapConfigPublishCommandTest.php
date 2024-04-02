<?php

declare(strict_types=1);

namespace Imdhemy\Purchases\Tests\Console;

use Illuminate\Support\Facades\File;
use Imdhemy\Purchases\Console\LiapConfigPublishCommand;
use Imdhemy\Purchases\Tests\TestCase;

class LiapConfigPublishCommandTest extends TestCase
{
    private string $configFilePath;

    protected function setUp(): void
    {
        parent::setUp();

        $this->configFilePath = config_path('liap.php');
    }

    /**
     * @test
     */
    public function it_should_publish_liap_config_file(): void
    {
        if (File::exists($this->configFilePath)) {
            unlink($this->configFilePath);
        }

        $this->artisan('liap:config:publish')
            ->expectsOutput(LiapConfigPublishCommand::MESSAGE_SUCCESS)
            ->assertSuccessful();

        $this->assertFileExists($this->configFilePath);
    }

    /**
     * @test
     *
     * @depends it_should_publish_liap_config_file
     */
    public function it_should_copy_the_contents_of_liap_config_file(): void
    {
        $expected = file_get_contents(__DIR__.'/../../config/liap.php');
        $actual = file_get_contents($this->configFilePath);

        $this->assertEquals($expected, $actual);
    }

    /**
     * @test
     *
     * @depends it_should_copy_the_contents_of_liap_config_file
     */
    public function it_should_fail_if_liap_config_file_already_published(): void
    {
        $this->artisan('liap:config:publish')
            ->expectsOutput(LiapConfigPublishCommand::MESSAGE_ALREADY_INSTALLED)
            ->assertFailed();
    }

    /**
     * @test
     *
     * @depends it_should_fail_if_liap_config_file_already_published
     */
    public function it_should_publish_liap_config_file_when_force_is_passed(): void
    {
        $this->artisan('liap:config:publish --force')
            ->expectsOutput(LiapConfigPublishCommand::MESSAGE_SUCCESS)
            ->assertSuccessful();
    }
}
