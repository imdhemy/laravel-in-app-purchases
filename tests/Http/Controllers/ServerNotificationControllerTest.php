<?php

declare(strict_types=1);

namespace Imdhemy\Purchases\Tests\Http\Controllers;

use Illuminate\Support\Facades\Event;
use Imdhemy\AppStore\Jws\JwsVerifier;
use Imdhemy\Purchases\Events\AppStore\DidChangeRenewalStatus;
use Imdhemy\Purchases\Events\AppStore\Subscribed;
use Imdhemy\Purchases\Events\GooglePlay\SubscriptionRecovered;
use Imdhemy\Purchases\Tests\TestCase;
use JsonException;

class ServerNotificationControllerTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->app->bind(JwsVerifier::class, \Imdhemy\Purchases\Tests\Doubles\JwsVerifier::class);
    }

    /**
     * @test
     */
    public function google_subscription_notification(): void
    {
        Event::fake();
        $this->withoutExceptionHandling();
        $data = [
            'message' => [
                'data' => $this->faker->googleSubscriptionNotification(),
            ],
        ];

        $uri = url('/liap/notifications?provider=google-play');

        $this->post($uri, $data)->assertStatus(200);

        Event::assertDispatched(SubscriptionRecovered::class);
    }

    /**
     * @test
     */
    public function google_test_notification(): void
    {
        file_put_contents(storage_path('logs/laravel.log'), '');
        $this->withoutExceptionHandling();
        $data = [
            'message' => [
                'data' => $this->faker->googleTestNotification(),
            ],
        ];

        $uri = url('/liap/notifications?provider=google-play');
        $this->post($uri, $data)->assertStatus(200);

        $this->assertNotEmpty(
            file_get_contents(storage_path('/logs/laravel.log'))
        );
    }

    /**
     * @test
     *
     * @throws JsonException
     */
    public function app_store_server_notifications(): void
    {
        $this->withoutExceptionHandling();

        Event::fake();
        $this->withoutExceptionHandling();

        $data = json_decode(
            file_get_contents($this->fixturesDir('appstore-server-notification.json')),
            true,
            512,
            JSON_THROW_ON_ERROR
        );
        $uri = url('/liap/notifications?provider=app-store');
        $this->post($uri, $data)->assertStatus(200);

        Event::assertDispatched(DidChangeRenewalStatus::class);
    }

    /**
     * @test
     *
     * @throws JsonException
     */
    public function it_logs_the_weird__zn_nk_weird_token(): void
    {
        file_put_contents(storage_path('logs/laravel.log'), '');

        $data = json_decode(
            file_get_contents($this->fixturesDir('weird-token-from-google.json')),
            true,
            512,
            JSON_THROW_ON_ERROR
        );
        $uri = url('/liap/notifications?provider=google-play');
        $this->post($uri, $data)->assertStatus(200);

        $this->assertNotEmpty(
            file_get_contents(storage_path('/logs/laravel.log'))
        );
    }

    /**
     * @test
     */
    public function app_store_test_notification(): void
    {
        file_put_contents(storage_path('logs/laravel.log'), '');
        $this->withoutExceptionHandling();

        $signedPayload = $this->faker->appStoreTestNotification();
        $uri = url('/liap/notifications?provider=app-store');

        $this->post($uri, ['signedPayload' => $signedPayload->toString()])->assertStatus(200);

        $logs = file_get_contents(storage_path('/logs/laravel.log'));
        $this->assertStringContainsString('AppStoreV2NotificationHandler: Test notification received', $logs);
    }

    /**
     * @test
     */
    public function app_store_server_notification_v2(): void
    {
        Event::fake();

        $signedPayload = $this->faker->appStoreNotification();
        $uri = url('/liap/notifications?provider=app-store');

        $this->post($uri, ['signedPayload' => $signedPayload->toString()])->assertStatus(200);

        Event::assertDispatched(Subscribed::class);
    }
}
