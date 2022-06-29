<?php

namespace Imdhemy\Purchases\ServiceProviders;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Imdhemy\Purchases\Console\LiapConfigPublishCommand;
use Imdhemy\Purchases\Product;
use Imdhemy\Purchases\Subscription;

/**
 * Laravel Iap service provider
 */
class LiapServiceProvider extends ServiceProvider
{
    public const CONFIG_PATH = __DIR__.'/../config/purchase.php';

    public const ROUTES_PATH = __DIR__.'/../routes/routes.php';

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishConfig();

        $this->bootRoutes();

        $this->bootCommands();
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(self::CONFIG_PATH, 'purchase');
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
            $this->loadRoutesFrom(self::ROUTES_PATH);
        });
    }

    /**
     * publish configurations
     */
    public function publishConfig(): void
    {
        if ($this->app->runningInConsole()) {
            $paths = [self::CONFIG_PATH => config_path('purchase.php')];
            $this->publishes($paths, 'config');
        }
    }

    /**
     * @return array
     */
    public function routeConfig(): array
    {
        return config('purchase.routing');
    }

    private function bootCommands(): void
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                LiapConfigPublishCommand::class,
            ]);
        }
    }
}
