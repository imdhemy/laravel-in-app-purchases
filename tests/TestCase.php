<?php

namespace Imdhemy\Purchases\Tests;

use Imdhemy\Purchases\PurchaseServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    /**
     * @inheritDoc
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->withFactories(__DIR__ . '/database/factories');
    }

    protected function getPackageProviders($app)
    {
        return [
            PurchaseServiceProvider::class,
        ];
    }

    public function getEnvironmentSetUp($app)
    {
        $path = __DIR__ . '/../google-app-credentials.json';
        putenv(sprintf("GOOGLE_APPLICATION_CREDENTIALS=%s", $path));

        $app['config']->set('database.default', 'sqlite');
        $app['config']->set('database.connections.sqlite', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]);

        $app['config']->set('purchase.google_play_package_name', 'com.twigano.v2');
    }
}
