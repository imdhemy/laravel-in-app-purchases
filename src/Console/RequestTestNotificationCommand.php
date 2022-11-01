<?php

declare(strict_types=1);

namespace Imdhemy\Purchases\Console;

use Illuminate\Console\Command;

/**
 * This command is used to request a test notification from Apple
 * It uses the configuration from the config file `liap.php`
 */
class RequestTestNotificationCommand extends Command
{
    /**
     * @inheritdoc
     */
    protected $signature = 'liap:apple:test-notification';

    /**
     * @inheritdoc
     */
    protected $description = 'Request a test notification from Apple';

    /**
     * Execute the console command
     *
     * @return int
     */
    public function handle(): int
    {
        return self::SUCCESS;
    }
}
