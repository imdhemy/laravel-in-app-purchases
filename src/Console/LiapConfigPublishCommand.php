<?php

namespace Imdhemy\Purchases\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Imdhemy\Purchases\ServiceProviders\LiapServiceProvider;

/**
 * Config publish command
 *
 * This command is used to publish LIAP configuration file
 */
class LiapConfigPublishCommand extends Command
{
    public const MESSAGE_ALREADY_INSTALLED = "liap.php is already published.";

    public const MESSAGE_SUCCESS = "liap.php published successfully";

    /**
     * @inheritdoc
     */
    protected $signature = "liap:config:publish {--f|force}";

    /**
     * @inheritdoc
     */
    protected $description = "Publishes the LIAP configuration file.";

    /**
     * Executes the console command
     *
     * @return int
     */
    public function handle(): int
    {
        if ($this->shouldForce()) {
            return $this->publishConfig(true);
        }

        if ($this->isInstalled()) {
            return $this->fail();
        }

        return $this->publishConfig();
    }

    /**
     * Checks if the command should force publish the configs
     *
     * @return bool
     */
    private function shouldForce(): bool
    {
        return (bool)$this->option('force');
    }

    /**
     * Publish configurations
     *
     * @param bool $force
     *
     * @return int
     */
    private function publishConfig(bool $force = false): int
    {
        $params = [
          '--provider' => LiapServiceProvider::class,
          '--tag' => 'config',
        ];

        if ($force) {
            $params['--force'] = true;
        }

        $result = $this->call('vendor:publish', $params);
        $this->info(self::MESSAGE_SUCCESS);

        return $result;
    }

    /**
     * Check if the freya is installed
     *
     * @return bool
     */
    private function isInstalled(): bool
    {
        return File::exists(config_path(LiapServiceProvider::CONFIG_KEY . '.php'));
    }

    /**
     * Should run on command failure.
     *
     * @return int
     */
    private function fail(): int
    {
        $this->error(self::MESSAGE_ALREADY_INSTALLED);

        return 1;
    }
}
