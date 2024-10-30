<?php

declare(strict_types=1);

namespace Imdhemy\Purchases\Tests\Console;

use Illuminate\Support\Facades\File;
use Imdhemy\Purchases\Tests\TestCase;

final class LiapConfigPublishCommandTest extends TestCase
{
    private string $configFilePath;
    private string $config;

    protected function setUp(): void
    {
        parent::setUp();

        $this->configFilePath = config_path('liap.php');
        $this->config = file_get_contents(__DIR__.'/../../config/liap.php');

        if (File::exists($this->configFilePath)) {
            unlink($this->configFilePath);
        }
    }

    /**
     * @test
     */
    public function publish_config_file(): void
    {
        $this->artisan('liap:config:publish')->assertSuccessful();

        $this->assertFileExists($this->configFilePath);
        $this->assertSame($this->config, file_get_contents($this->configFilePath));
    }

    /**
     * @test
     */
    public function it_should_file_if_config_is_published(): void
    {
        $this->artisan('liap:config:publish');

        $this->artisan('liap:config:publish')->assertFailed();
    }

    /**
     * @test
     */
    public function publishing_config_file_can_be_forced(): void
    {
        $this->artisan('liap:config:publish');

        $this->artisan('liap:config:publish --force')->assertSuccessful();
        $this->assertFileExists($this->configFilePath);
        $this->assertSame($this->config, file_get_contents($this->configFilePath));
    }
}
