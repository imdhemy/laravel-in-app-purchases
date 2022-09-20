<?php

namespace Tests\Http\Controllers;

use Illuminate\Support\Facades\Event;
use Imdhemy\Purchases\Events\AppStore\DidChangeRenewalStatus;
use Imdhemy\Purchases\Events\GooglePlay\SubscriptionRecovered;
use JsonException;
use Tests\TestCase;

class ServerNotificationControllerTest extends TestCase
{
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
        file_put_contents(storage_path('logs/laravel.log'), "");
        $this->withoutExceptionHandling();
        $data = [
            'message' => [
                'data' => $this->faker->googleTestNotification(),
            ],
        ];

        $uri = url('/liap/notifications?provider=google-play');
        $this->post($uri, $data)->assertStatus(200);

        $this->assertNotEmpty(
            file_get_contents(storage_path("/logs/laravel.log"))
        );
    }

    /**
     * @test
     * @throws JsonException
     */
    public function app_store_server_notifications(): void
    {
        $this->withoutExceptionHandling();

        Event::fake();
        $this->withoutExceptionHandling();

        $data = json_decode(
            file_get_contents($this->testAssetPath('appstore-server-notification.json')),
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
     * @throws JsonException
     */
    public function it_logs_the_weird_ZnNk_weird_token(): void
    {
        file_put_contents(storage_path('logs/laravel.log'), "");

        $data = json_decode(
            file_get_contents($this->testAssetPath('weird-token-from-google.json')),
            true,
            512,
            JSON_THROW_ON_ERROR
        );
        $uri = url('/liap/notifications?provider=google-play');
        $this->post($uri, $data)->assertStatus(200);

        $this->assertNotEmpty(
            file_get_contents(storage_path("/logs/laravel.log"))
        );
    }
}
