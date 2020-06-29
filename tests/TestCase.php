<?php

namespace Imdhemy\Purchases\Tests;

use Imdhemy\Purchases\PurchaseServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    const GOOGLE_PLAY_PACKAGE = 'purchases.google_play_package';
    const SUBSCRIPTION_ID = 'purchases.subscription_id';
    const PURCHASE_TOKEN = 'purchases.purchase_token';
    const GOOGLE_APP_CREDENTIALS = 'purchases.google_app_credentials';

    public function setUp(): void
    {
        parent::setUp();

        $this->withFactories(__DIR__.'/database/factories');
    }

    protected function getPackageProviders($app)
    {
        return [
            PurchaseServiceProvider::class,
        ];
    }

    public function getEnvironmentSetUp($app)
    {
        $app['config']->set('database.default', 'sqlite');
        $app['config']->set('database.connections.sqlite', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]);

        $app['config']->set(self::GOOGLE_PLAY_PACKAGE, 'com.twigano.fashion');
        $app['config']->set(self::SUBSCRIPTION_ID . '', 'week_premium');
        $app['config']->set(self::PURCHASE_TOKEN, 'ghpmfmednnbjkcheljjpdnbn.AO-J1OzOqWsD57dURPVrKYh2Qv-t5Lx9VJtCFLdxMovzAgfdF1CwX35AbH3RYRhAMqApdlgLvw7v1Eog43rWYGhGXODl9_Ir9O2YqcXqLSPM7ojuVr9mpcmUha2LZf3YaCbowk1UJfuc');
        $app['config']->set(self::GOOGLE_APP_CREDENTIALS, 'google-app-credentials.json');

        include_once __DIR__ . '/../database/migrations/create_purchase_logs_table.php.stub';
        (new \CreatePurchaseLogsTable())->up();
    }
}
