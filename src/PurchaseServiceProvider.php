<?php

namespace Imdhemy\Purchases;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

/**
 * Class PurchaseServiceProvider
 * @package Imdhemy\Purchases
 */
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
            $this->publishConfig();
        }

        $this->bootRoutes();
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/purchase.php', 'purchase');
        $this->app->register(EventServiceProvider::class);

        $this->app->bind('product', function () {
            return new Product();
        });

        $this->app->bind('subscription', function () {
            return new Subscription();
        });
    }

    /**
     * boots routes
     */
    public function bootRoutes(): void
    {
        Route::group($this->routeConfig(), function () {
            $this->loadRoutesFrom(__DIR__ . '/routes/routes.php');
        });
    }

    /**
     * publish configurations
     */
    public function publishConfig(): void
    {
        $paths = [__DIR__ . '/../config/purchase.php' => config_path('purchase.php')];
        $this->publishes($paths, 'config');
    }

    /**
     * @return array
     */
    public function routeConfig(): array
    {
        return config('purchase.routing');
    }
}
