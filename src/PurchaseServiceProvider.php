<?php

namespace Imdhemy\Purchases;

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Imdhemy\GooglePlay\ClientFactory;

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

        $this->app->bind('product', function (Application $app) {
            $client = ClientFactory::create([ClientFactory::SCOPE_ANDROID_PUBLISHER]);
            $packageName = $app['config']->get('purchase.google_play_package_name');

            return new Product($client, $packageName);
        });

        $this->app->bind('subscription', function (Application $app) {
            $client = ClientFactory::create([ClientFactory::SCOPE_ANDROID_PUBLISHER]);

            return new Subscription($client);
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
