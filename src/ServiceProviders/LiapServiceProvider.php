<?php

namespace Imdhemy\Purchases\ServiceProviders;

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Imdhemy\Purchases\Console\LiapConfigPublishCommand;
use Imdhemy\Purchases\Console\LiapUrlCommand;
use Imdhemy\Purchases\Console\UrlGenerator;
use Imdhemy\Purchases\Contracts\UrlGenerator as UrlGeneratorContract;
use Imdhemy\Purchases\Product;
use Imdhemy\Purchases\Subscription;
use Tests\Doubles\UrlGeneratorDouble;

/**
 * Laravel Iap service provider
 */
class LiapServiceProvider extends ServiceProvider
{
    public const CONFIG_KEY = 'liap';

    public const CONFIG_PATH = __DIR__ . '/../../config/' . self::CONFIG_KEY . '.php';

    public const ROUTES_PATH = __DIR__ . '/../../routes/routes.php';

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
            $paths = [self::CONFIG_PATH => config_path(self::CONFIG_KEY . '.php')];
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
        return config(self::CONFIG_KEY . '.routing');
    }

    /**
     * Boots console commands
     */
    private function bootCommands(): void
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
              LiapConfigPublishCommand::class,
              LiapUrlCommand::class,
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

        $this->bindConcretes();
    }

    /**
     * Merges published configurations with default config
     */
    private function registerConfig(): void
    {
        $this->mergeConfigFrom(self::CONFIG_PATH, self::CONFIG_KEY);
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

    /**
     * Bind concrete classes
     *
     * @return void
     */
    private function bindConcretes(): void
    {
        $this->app->bind(UrlGeneratorContract::class, UrlGenerator::class);

        $this->app->when(LiapUrlCommand::class)
          ->needs(UrlGeneratorContract::class)
          ->give(function (Application $app) {
              $concrete = $app->runningUnitTests() ? UrlGeneratorDouble::class : UrlGenerator::class;

              return $app->make($concrete);
          });
    }
}
