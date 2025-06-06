<?php

declare(strict_types=1);

namespace Imdhemy\Purchases\Tests\Feature;

use Illuminate\Support\Facades\Event;
use Imdhemy\AppStore\Jws\JwsVerifier;
use Imdhemy\Purchases\Events\AppStore\Subscribed;
use Imdhemy\Purchases\Tests\TestCase;

final class HandleAppStoreNotificationFeatureTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->app->bind(JwsVerifier::class, \Imdhemy\Purchases\Tests\Doubles\JwsVerifier::class);
    }

    /** @test */
    public function handle_app_store_test_notification(): void
    {
        file_put_contents(storage_path('logs/laravel.log'), '');
        $this->withoutExceptionHandling();
        $signedPayload = $this->faker->appStoreTestNotification();

        $this->post('/liap/notifications?provider=app-store', ['signedPayload' => $signedPayload->toString()]
        )->assertStatus(200);

        $logs = file_get_contents(storage_path('/logs/laravel.log'));
        $this->assertStringContainsString('AppStoreV2NotificationHandler: Test notification received', $logs);
    }

    /** @test */
    public function handle_app_store_server_notification_v2(): void
    {
        Event::fake();
        $signedPayload = $this->faker->appStoreNotification();

        $this->post('/liap/notifications?provider=app-store', ['signedPayload' => $signedPayload->toString()]
        )->assertStatus(200);

        Event::assertDispatched(Subscribed::class);
    }
}
