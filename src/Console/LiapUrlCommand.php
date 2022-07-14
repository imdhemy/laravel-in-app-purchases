<?php

namespace Imdhemy\Purchases\Console;

use Illuminate\Console\Command;

/**
 * A command to generate signed url to the server notification handler endpoint
 */
class LiapUrlCommand extends Command
{
    public const CHOICE_PROVIDER = 'Select provider';

    public const PROVIDER_ALL = 'All Providers';

    public const PROVIDER_APP_STORE = 'App Store';

    public const PROVIDER_GOOGLE_PLAY = 'Google Play';

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
        $provider = $this->choice(self::CHOICE_PROVIDER, [
          self::PROVIDER_ALL,
          self::PROVIDER_APP_STORE,
          self::PROVIDER_GOOGLE_PLAY,
        ]);

        return 0;
    }
}
