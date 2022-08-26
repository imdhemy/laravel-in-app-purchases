<?php

namespace Tests\Doubles;

use Illuminate\Foundation\Application;
use Illuminate\Support\ServiceProvider;
use Imdhemy\Purchases\Console\LiapUrlCommand;
use Imdhemy\Purchases\Console\UrlGenerator;
use Imdhemy\Purchases\Contracts\UrlGenerator as UrlGeneratorContract;

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
        $this->app->when(LiapUrlCommand::class)
            ->needs(UrlGeneratorContract::class)
            ->give(function (Application $app) {
                $concrete = $app->runningUnitTests() ? UrlGeneratorDouble::class : UrlGenerator::class;

                return $app->make($concrete);
            });
    }
}
