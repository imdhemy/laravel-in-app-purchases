<?php

declare(strict_types=1);

namespace Imdhemy\Purchases\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Imdhemy\Purchases\ServiceProviders\LiapServiceProvider;

/**
 * This command is used to publish LIAP configuration file.
 */
final class LiapConfigPublishCommand extends Command
{
    protected $signature = 'liap:config:publish {--f|force}';
    protected $description = 'Publishes the LIAP configuration file.';

    public function handle(): int
    {
        if ($this->shouldForce()) {
            return $this->publishConfig(true);
        }

        if ($this->isPublished()) {
            return $this->publishFailed();
        }

        return $this->publishConfig();
    }

    private function shouldForce(): bool
    {
        return (bool)$this->option('force');
    }

    private function publishConfig(bool $force = false): int
    {
        $params = [
            '--provider' => LiapServiceProvider::class,
            '--tag' => 'config',
        ];

        if ($force) {
            $params['--force'] = true;
        }

        return $this->call('vendor:publish', $params);
    }

    private function isPublished(): bool
    {
        return File::exists(config_path(LiapServiceProvider::CONFIG_KEY.'.php'));
    }

    private function publishFailed(): int
    {
        $this->error('liap.php is already published.');

        return self::FAILURE;
    }
}
