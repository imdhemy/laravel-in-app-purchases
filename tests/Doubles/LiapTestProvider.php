<?php

declare(strict_types=1);

namespace Imdhemy\Purchases\Tests\Doubles;

use Illuminate\Support\ServiceProvider;
use Imdhemy\Purchases\Services\AppStoreTestNotificationServiceBuilder;
use Imdhemy\Purchases\Tests\Doubles\AppStoreTestNotificationServiceBuilder as FakeBuilder;

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
        $this->app->bind(
            AppStoreTestNotificationServiceBuilder::class,
            FakeBuilder::class
        );
    }
}
