<?php

declare(strict_types=1);

namespace Imdhemy\Purchases\Tests\Feature;

use Illuminate\Support\Facades\Event;
use Imdhemy\AppStore\Jws\JwsVerifier;
use Imdhemy\Purchases\Domain\Event\AppStoreNotificationReceivedEvent;
use Imdhemy\Purchases\Events\AppStore\Subscribed;
use Imdhemy\Purchases\Tests\Doubles\JwsVerifier as FakeJwsVerifier;
use Imdhemy\Purchases\Tests\TestCase;

final class HandleAppStoreNotificationFeatureTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->app->bind(JwsVerifier::class, FakeJwsVerifier::class);
        $this->withoutExceptionHandling();
        Event::fake();
    }

    /** @test */
    public function it_dispatches_server_notification_event(): void
    {
        $data = ['signedPayload' => $this->faker->appStoreNotification()->toString()];

        $response = $this->post('/liap/notifications?provider=app-store', $data);

        $response->assertStatus(200);
        Event::assertDispatched(Subscribed::class);
        Event::assertDispatched(AppStoreNotificationReceivedEvent::class);
    }

    /** @test */
    public function it_logs_test_notification(): void
    {
        $data = ['signedPayload' => $this->faker->appStoreTestNotification()->toString()];

        $response = $this->post('/liap/notifications?provider=app-store', $data);

        $response->assertStatus(200);
        $this->assertLogsContain('AppStoreV2NotificationHandler: Test notification received');
        Event::assertDispatched(AppStoreNotificationReceivedEvent::class);
    }

    protected function tearDown(): void
    {
        $this->clearLogs();

        parent::tearDown();
    }
}
