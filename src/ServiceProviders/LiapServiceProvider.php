<?php

declare(strict_types=1);

namespace Imdhemy\Purchases\ServiceProviders;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Imdhemy\AppStore\Jws\AppStoreJwsVerifier;
use Imdhemy\AppStore\Jws\JwsParser;
use Imdhemy\AppStore\Jws\JwsVerifier;
use Imdhemy\AppStore\Jws\Parser;
use Imdhemy\Purchases\Console\LiapConfigPublishCommand;
use Imdhemy\Purchases\Console\LiapUrlCommand;
use Imdhemy\Purchases\Console\RequestTestNotificationCommand;
use Imdhemy\Purchases\Console\UrlGenerator;
use Imdhemy\Purchases\Contracts\EventFactory as EventFactoryContract;
use Imdhemy\Purchases\Contracts\UrlGenerator as UrlGeneratorContract;
use Imdhemy\Purchases\Events\EventFactory;
use Imdhemy\Purchases\Handlers\HandlerHelpers;
use Imdhemy\Purchases\Handlers\HandlerHelpersInterface;
use Imdhemy\Purchases\Handlers\JwsService;
use Imdhemy\Purchases\Handlers\JwsServiceInterface;
use Imdhemy\Purchases\Product;
use Imdhemy\Purchases\Subscription;
use Lcobucci\JWT\Decoder;
use Lcobucci\JWT\Encoding\JoseEncoder;

/**
 * Laravel Iap service provider.
 */
class LiapServiceProvider extends ServiceProvider
{
    public const CONFIG_KEY = 'liap';

    public const CONFIG_PATH = __DIR__.'/../../config/'.self::CONFIG_KEY.'.php';

    public const ROUTES_PATH = __DIR__.'/../../routes/routes.php';

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->publishConfig();

        $this->bootRoutes();

        $this->bootCommands();
    }

    /**
     * publishes configurations.
     */
    public function publishConfig(): void
    {
        if ($this->app->runningInConsole()) {
            $paths = [self::CONFIG_PATH => config_path(self::CONFIG_KEY.'.php')];
            $this->publishes($paths, 'config');
        }
    }

    /**
     * Boots routes.
     */
    public function bootRoutes(): void
    {
        Route::group($this->routeConfig(), function () {
            $this->loadRoutesFrom(self::ROUTES_PATH);
        });
    }

    /**
     * Gets routes configuration.
     */
    private function routeConfig(): array
    {
        return (array)config(self::CONFIG_KEY.'.routing');
    }

    /**
     * Boots console commands.
     */
    private function bootCommands(): void
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                LiapConfigPublishCommand::class,
                LiapUrlCommand::class,
                RequestTestNotificationCommand::class,
            ]);
        }
    }

    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->registerConfig();

        $this->registerEvents();

        $this->bindFacades();

        $this->bindConcretes();
    }

    /**
     * Merges published configurations with default config.
     */
    private function registerConfig(): void
    {
        $this->mergeConfigFrom(self::CONFIG_PATH, self::CONFIG_KEY);

        $googleApplicationCredentials = (string)config('liap.google_application_credentials');

        if (! empty($googleApplicationCredentials) && file_exists($googleApplicationCredentials)) {
            putenv("GOOGLE_APPLICATION_CREDENTIALS=$googleApplicationCredentials");
        }
    }

    /**
     * Registers LIAP event service provider.
     */
    private function registerEvents(): void
    {
        $this->app->register(EventServiceProvider::class);
    }

    /**
     * Binds facades.
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
     * Bind concrete classes.
     */
    private function bindConcretes(): void
    {
        // Bind UrlGenerator
        $this->app->bind(UrlGeneratorContract::class, UrlGenerator::class);

        // Bind JWS
        $this->app->bind(JwsParser::class, Parser::class);
        $this->app->bind(JwsVerifier::class, AppStoreJwsVerifier::class);
        $this->app->bind(Decoder::class, JoseEncoder::class);

        // Bind Handlers
        $this->app->bind(EventFactoryContract::class, EventFactory::class);
        $this->app->bind(HandlerHelpersInterface::class, HandlerHelpers::class);
        $this->app->bind(JwsServiceInterface::class, JwsService::class);
    }
}
