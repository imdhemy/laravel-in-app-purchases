<?php

namespace Imdhemy\Purchases;

use Illuminate\Support\ServiceProvider;

class PurchaseServiceProvider extends ServiceProvider
{
    const MIGRATION_STUB = '/../database/migrations/create_purchase_logs_table.php.stub';
    const MIGRATION_NAME = '_create_purchase_logs_table.php';
    const GROUP_MIGRATIONS = 'migrations';
    const CONFIG_PATH = '/../config/purchases.php';
    const CONFIG_NAME = 'purchases.php';
    const GROUP_CONFIG = 'config';

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
        if (! class_exists('CreateSubscriptionPurchasesTable')) {
            $path = 'migrations/' . date('Y_m_d_His', time()) . self::MIGRATION_NAME;
            $migrationPath = database_path($path);
            $stubPath = __DIR__ . self::MIGRATION_STUB;
            $this->publishes([$stubPath => $migrationPath,], self::GROUP_MIGRATIONS);
        }
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
