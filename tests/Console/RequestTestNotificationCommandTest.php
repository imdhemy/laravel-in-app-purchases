<?php

declare(strict_types=1);

namespace Imdhemy\Purchases\Tests\Console;

use Imdhemy\Purchases\Tests\Doubles\AppStoreTestNotificationServiceBuilder as FakeAppStoreTestNotificationServiceBuilder;
use Imdhemy\Purchases\Tests\TestCase;
use Symfony\Component\Console\Command\Command;

class RequestTestNotificationCommandTest extends TestCase
{
    private string $privateKey;

    protected function setUp(): void
    {
        parent::setUp();

        $this->privateKey = $this->generateP8Key();
    }

    /**
     * @test
     */
    public function it_validates_private_key_id_is_configured(): void
    {
        $this->artisan('liap:apple:test-notification')
            ->expectsOutput('The private key ID is not configured')
            ->assertExitCode(Command::FAILURE);
    }

    /**
     * @test
     */
    public function it_validates_private_key_is_configured(): void
    {
        $this->app['config']->set('liap.appstore_private_key_id', 'private-key-id');

        $this->artisan('liap:apple:test-notification')
            ->expectsOutput('The private key is not configured')
            ->assertExitCode(Command::FAILURE);
    }

    /**
     * @test
     */
    public function it_validates_issuer_id_is_configured(): void
    {
        $this->app['config']->set('liap.appstore_private_key_id', 'private-key-id');
        $this->app['config']->set('liap.appstore_private_key', $this->privateKey);

        $this->artisan('liap:apple:test-notification')
            ->expectsOutput('The issuer ID is not configured')
            ->assertExitCode(Command::FAILURE);
    }

    /**
     * @test
     */
    public function it_validates_bundle_id_is_configured(): void
    {
        $this->app['config']->set('liap.appstore_private_key_id', 'private-key-id');
        $this->app['config']->set('liap.appstore_private_key', $this->privateKey);
        $this->app['config']->set('liap.appstore_issuer_id', 'issuer-id');

        $this->artisan('liap:apple:test-notification')
            ->expectsOutput('The bundle ID is not configured')
            ->assertExitCode(Command::FAILURE);
    }

    /**
     * @test
     */
    public function it_requests_a_test_notification(): void
    {
        $this->app['config']->set('liap.appstore_private_key_id', 'private-key-id');
        $this->app['config']->set('liap.appstore_private_key', $this->privateKey);
        $this->app['config']->set('liap.appstore_issuer_id', 'issuer-id');
        $this->app['config']->set('liap.appstore_bundle_id', 'bundle-id');

        $this->artisan('liap:apple:test-notification')
            ->expectsOutput(
                sprintf('Test notification token: %s', FakeAppStoreTestNotificationServiceBuilder::PRODUCTION_TOKEN)
            )
            ->assertExitCode(Command::SUCCESS);
    }

    /**
     * @test
     */
    public function it_can_request_a_test_notification_from_sandbox_server(): void
    {
        $this->app['config']->set('liap.appstore_private_key_id', 'private-key-id');
        $this->app['config']->set('liap.appstore_private_key', $this->privateKey);
        $this->app['config']->set('liap.appstore_issuer_id', 'issuer-id');
        $this->app['config']->set('liap.appstore_bundle_id', 'bundle-id');

        $this->artisan('liap:apple:test-notification --sandbox')
            ->expectsOutput(
                sprintf('Test notification token: %s', FakeAppStoreTestNotificationServiceBuilder::SANDBOX_TOKEN)
            )
            ->assertExitCode(Command::SUCCESS);
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        $this->deleteFile($this->privateKey);
    }
}
