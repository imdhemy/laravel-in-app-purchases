<?php

namespace Imdhemy\Purchases\Console;

use Illuminate\Console\Command;

/**
 * A command to generate signed url to the server notification handler endpoint
 */
class LiapUrlCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = "liap:url";

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "Generates a signed URL to the server notifications handler endpoint";

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(): int
    {
        return 0;
    }
}
