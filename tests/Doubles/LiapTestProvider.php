<?php

namespace Imdhemy\Purchases\Tests\Doubles;

use Illuminate\Support\ServiceProvider;
use Imdhemy\Purchases\Contracts\UrlGenerator as UrlGeneratorContract;
use Imdhemy\Purchases\Services\AppStoreTestNotificationServiceBuilder;

/**
 * Service provider for testing purposes
 */
class LiapTestProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(): void
    {
        //
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register(): void
    {
        $this->app->bind(
            UrlGeneratorContract::class,
            UrlGeneratorDouble::class
        );

        $this->app->bind(
            AppStoreTestNotificationServiceBuilder::class,
            AppStoreTestNotificationServiceBuilderDouble::class
        );
    }
}
