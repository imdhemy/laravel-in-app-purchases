<?php

declare(strict_types=1);

namespace Imdhemy\Purchases\Tests\Feature;

use Illuminate\Support\Facades\Event;
use Imdhemy\Purchases\Events\GooglePlay\SubscriptionRecovered;
use Imdhemy\Purchases\Tests\TestCase;

final class HandleGoogleNotificationFeatureTest extends TestCase
{
    /** @test */
    public function handle_google_subscription_notification(): void
    {
        Event::fake();
        $this->withoutExceptionHandling();
        $data = [
            'message' => [
                'data' => $this->faker->googleSubscriptionNotification(),
            ],
        ];

        $response = $this->post('/liap/notifications?provider=google-play', $data);

        $response->assertStatus(200);
        Event::assertDispatched(SubscriptionRecovered::class);
    }

    /** @test */
    public function handle_google_test_notification(): void
    {
        Event::fake();
        file_put_contents(storage_path('logs/laravel.log'), '');
        $this->withoutExceptionHandling();
        $data = [
            'message' => [
                'data' => $this->faker->googleTestNotification(),
            ],
        ];

        $response = $this->post('/liap/notifications?provider=google-play', $data);

        $response->assertStatus(200);
        $this->assertNotEmpty(file_get_contents(storage_path('/logs/laravel.log')));
    }
}
