<?php

declare(strict_types=1);

namespace Imdhemy\Purchases\Tests\Feature;

use Illuminate\Support\Facades\Event;
use Imdhemy\Purchases\Domain\Event\GooglePlayNotificationReceivedEvent;
use Imdhemy\Purchases\Events\GooglePlay\SubscriptionRecovered;
use Imdhemy\Purchases\Tests\TestCase;

final class HandleGoogleNotificationFeatureTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutExceptionHandling();
        Event::fake();
    }

    /** @test */
    public function it_dispatches_server_notification_test(): void
    {
        $data = [
            'message' => [
                'data' => $this->faker->googleSubscriptionNotification(),
            ],
        ];

        $response = $this->post('/liap/notifications?provider=google-play', $data);

        $response->assertStatus(200);
        Event::assertDispatched(SubscriptionRecovered::class);
        Event::assertDispatched(GooglePlayNotificationReceivedEvent::class);
    }

    /** @test */
    public function it_logs_test_notification(): void
    {
        $data = [
            'message' => [
                'data' => $this->faker->googleTestNotification(),
            ],
        ];

        $response = $this->post('/liap/notifications?provider=google-play', $data);

        $response->assertStatus(200);
        $this->assertLogsContain('Google Play Test Notification, version: 1.0');
        Event::assertDispatched(GooglePlayNotificationReceivedEvent::class);
    }

    /** @test */
    public function it_logs_the_weird__zn_nk_weird_token(): void
    {
        $data = json_decode(
            file_get_contents($this->fixturesDir('weird-token-from-google.json')),
            true,
            512,
            JSON_PARTIAL_OUTPUT_ON_ERROR
        );
        $this->post('/liap/notifications?provider=google-play', $data)->assertStatus(200);

        $this->assertLogsContain('Google Play malformed RTDN');
        Event::assertDispatched(GooglePlayNotificationReceivedEvent::class);
    }

    protected function tearDown(): void
    {
        $this->clearLogs();

        parent::tearDown();
    }
}
