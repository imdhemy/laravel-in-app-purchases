<?php

namespace Imdhemy\Purchases;

use Illuminate\Support\ServiceProvider;

class PurchaseServiceProvider extends ServiceProvider
{
    const GROUP_MIGRATIONS = 'migrations';
    const CONFIG_PATH = '/../config/purchases.php';
    const CONFIG_NAME = 'purchases.php';
    const GROUP_CONFIG = 'config';
    const MIGRATIONS = [
        'create_purchase_logs_table',
        'create_failed_renewals_table',
    ];

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->publishConfig();

            $this->publishMigrations();
        }
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . self::CONFIG_PATH, 'purchases');
    }

    /**
     * @return void
     */
    private function publishMigrations(): void
    {
        $paths = [];
        foreach (self::MIGRATIONS as $migration) {
            $path = sprintf("migrations/%s_%s.php", date('Y_m_d_His', time()), $migration);
            $migrationPath = database_path($path);
            $stubPath = __DIR__ . '/'. $migration . ".php.stub";
            $paths[$stubPath] = $migrationPath;
        }
        $this->publishes($paths, self::GROUP_MIGRATIONS);
    }

    /**
     * @return void
     */
    private function publishConfig(): void
    {
        $packageConfig = __DIR__ . self::CONFIG_PATH;
        $appConfig = config_path(self::CONFIG_NAME);
        $this->publishes([$packageConfig => $appConfig,], self::GROUP_CONFIG);
    }
}
