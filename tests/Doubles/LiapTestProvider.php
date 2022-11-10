<?php

declare(strict_types=1);

namespace Tests\Doubles;

use Illuminate\Foundation\Application;
use Illuminate\Support\ServiceProvider;
use Imdhemy\Purchases\Console\LiapUrlCommand;
use Imdhemy\Purchases\Console\RequestTestNotificationCommand;
use Imdhemy\Purchases\Console\UrlGenerator;
use Imdhemy\Purchases\Contracts\UrlGenerator as UrlGeneratorContract;
use Imdhemy\Purchases\Services\AppStoreTestNotificationServiceBuilder;

/**
 * Service provider for testing purposes.
 */
class LiapTestProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
    }

    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->when(LiapUrlCommand::class)
            ->needs(UrlGeneratorContract::class)
            ->give(function (Application $app) {
                $concrete = $app->runningUnitTests() ? UrlGeneratorDouble::class : UrlGenerator::class;

                return $app->make($concrete);
            });

        $this->app->when(RequestTestNotificationCommand::class)
            ->needs(AppStoreTestNotificationServiceBuilder::class)
            ->give(function (Application $app) {
                $concrete = $app->runningUnitTests() ?
                    AppStoreTestNotificationServiceBuilderDouble::class : AppStoreTestNotificationServiceBuilder::class;

                return $app->make($concrete);
            });
    }
}
