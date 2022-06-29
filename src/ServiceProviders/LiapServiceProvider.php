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
     * publishes configurations
     */
    public function publishConfig(): void
    {
        if ($this->app->runningInConsole()) {
            $paths = [self::CONFIG_PATH => config_path('purchase.php')];
            $this->publishes($paths, 'config');
        }
    }

    /**
     * Boots routes
     */
    public function bootRoutes(): void
    {
        Route::group($this->routeConfig(), function () {
            $this->loadRoutesFrom(self::ROUTES_PATH);
        });
    }

    /**
     * Gets routes configuration
     *
     * @return array
     */
    private function routeConfig(): array
    {
        return config('purchase.routing');
    }

    /**
     * Boots console commands
     */
    private function bootCommands(): void
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                LiapConfigPublishCommand::class,
            ]);
        }
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->registerConfig();

        $this->registerEvents();

        $this->bindFacades();
    }

    /**
     * Merges published configurations with default config
     */
    private function registerConfig(): void
    {
        $this->mergeConfigFrom(self::CONFIG_PATH, 'purchase');
    }

    /**
     * Registers LIAP event service provider
     */
    private function registerEvents(): void
    {
        $this->app->register(EventServiceProvider::class);
    }

    /**
     * Binds facades
     */
    private function bindFacades(): void
    {
        $this->app->bind('product', function () {
            return new Product();
        });

        $this->app->bind('subscription', function () {
            return new Subscription();
        });
    }
}
