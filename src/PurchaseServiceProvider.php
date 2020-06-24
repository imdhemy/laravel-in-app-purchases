<?php

namespace Imdhemy\Purchases;

use Illuminate\Support\ServiceProvider;

class PurchaseServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            if (! class_exists('CreateSubscriptionPurchasesTable')) {
                $this->publishes([
                    __DIR__ . '/../database/migrations/create_purchases_logs_table.php.stub' => database_path('migrations/' . date('Y_m_d_His', time()) . '_create_subscription_purchases_table.php'),
                ], 'migrations');
            }
        }
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/purchases.php', 'purchases');
    }
}
