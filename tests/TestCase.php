<?php

namespace Tests;

use Imdhemy\Purchases\PurchaseServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

/**
 * Test Case
 * All test cases that requires a laravel app instance
 * should extend this class
 */
class TestCase extends Orchestra
{
    /**
     * @inheritDoc
     */
    public function setUp(): void
    {
        parent::setUp();
    }

    /**
     * @inheritdoc
     */
    protected function getPackageProviders($app): array
    {
        return [
            PurchaseServiceProvider::class,
        ];
    }

    /**
     * @inheritdoc
     */
    public function getEnvironmentSetUp($app)
    {
        $app['config']->set('database.default', 'sqlite');
        $app['config']->set('database.connections.sqlite', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]);
    }
}
