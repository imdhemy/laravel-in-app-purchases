<?php

namespace Imdhemy\Purchases\Tests;

use Imdhemy\Purchases\PurchaseServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    const GOOGLE_PLAY_PACKAGE = 'com.twigano.fashion';
    const SUBSCRIPTION_ID = 'week_premium';
    const PURCHASE_TOKEN = 'ghpmfmednnbjkcheljjpdnbn.AO-J1OzOqWsD57dURPVrKYh2Qv-t5Lx9VJtCFLdxMovzAgfdF1CwX35AbH3RYRhAMqApdlgLvw7v1Eog43rWYGhGXODl9_Ir9O2YqcXqLSPM7ojuVr9mpcmUha2LZf3YaCbowk1UJfuc';
    const GOOGLE_APP_CREDENTIALS_JSON = 'google-app-credentials.json';

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

        $app['config']->set('purchases.google_play_package', self::GOOGLE_PLAY_PACKAGE);
        $app['config']->set('purchases.subscription_id', self::SUBSCRIPTION_ID);
        $app['config']->set('purchases.purchase_token', self::PURCHASE_TOKEN);
        $app['config']->set('purchases.google_app_credentials', self::GOOGLE_APP_CREDENTIALS_JSON);

        include_once __DIR__ . '/../database/migrations/create_purchase_logs_table.php.stub';
        (new \CreatePurchaseLogsTable())->up();
    }
}
