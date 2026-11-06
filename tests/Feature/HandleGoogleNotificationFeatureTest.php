<?php

declare(strict_types=1);

namespace Imdhemy\Purchases\Tests\Feature;

use Illuminate\Support\Facades\Event;
use Imdhemy\GooglePlay\Domain\Rtdn\Type\OneTimeProductNotificationType;
use Imdhemy\GooglePlay\Infrastructure\Transformer\Normalizer;
use Imdhemy\GooglePlay\Interface\Rtdn\NotificationParser;
use Imdhemy\Purchases\Domain\Event\GooglePlayNotificationReceivedEvent;
use Imdhemy\Purchases\Events\FallbackEvent;
use Imdhemy\Purchases\Events\GooglePlay\SubscriptionRecovered;
use Imdhemy\Purchases\Tests\TestCase;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;

final class HandleGoogleNotificationFeatureTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutExceptionHandling();
        Event::fake();
    }

    #[Test]
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
        Event::assertDispatched(
            GooglePlayNotificationReceivedEvent::class,
            static fn (GooglePlayNotificationReceivedEvent $event,
            ) => $event->payload->message->data === $data['message']['data']
        );
    }

    #[Test]
    public function it_dispatches_the_received_event_once_for_a_subscription_notification(): void
    {
        $data = [
            'message' => [
                'data' => $this->faker->googleSubscriptionNotification(),
            ],
        ];

        $this->post('/liap/notifications?provider=google-play', $data)->assertStatus(200);

        Event::assertDispatchedTimes(GooglePlayNotificationReceivedEvent::class);
    }

    #[Test]
    #[DataProvider('oneTimeProductNotificationTypes')]
    public function it_dispatches_the_received_event_for_a_one_time_product_notification(
        OneTimeProductNotificationType $type,
    ): void {
        $data = [
            'message' => [
                'data' => $this->faker->googleOneTimeProductNotification(['notificationType' => $type->value]),
            ],
        ];

        $response = $this->post('/liap/notifications?provider=google-play', $data);

        $response->assertStatus(200);
        Event::assertDispatchedTimes(GooglePlayNotificationReceivedEvent::class);
        Event::assertDispatched(
            GooglePlayNotificationReceivedEvent::class,
            static function (GooglePlayNotificationReceivedEvent $event) use ($type): bool {
                $notification = new NotificationParser(Normalizer::create())->parse([
                    'message' => ['data' => $event->payload->message->data],
                ]);

                return $notification->oneTimeProductNotification?->notificationType === $type
                    && 'some-sku' === $notification->oneTimeProductNotification->sku;
            }
        );
    }

    /**
     * @return array<string, array{OneTimeProductNotificationType}>
     */
    public static function oneTimeProductNotificationTypes(): array
    {
        return [
            'purchased' => [OneTimeProductNotificationType::PURCHASED],
            'canceled' => [OneTimeProductNotificationType::CANCELED],
        ];
    }

    #[Test]
    public function it_does_not_dispatch_a_subscription_lifecycle_event_for_a_one_time_product_notification(): void
    {
        $data = [
            'message' => [
                'data' => $this->faker->googleOneTimeProductNotification(),
            ],
        ];

        $this->post('/liap/notifications?provider=google-play', $data)->assertStatus(200);

        Event::assertNotDispatched(SubscriptionRecovered::class);
        Event::assertNotDispatched(FallbackEvent::class);
    }

    #[Test]
    public function it_dispatches_the_received_event_for_a_voided_purchase_notification(): void
    {
        $data = [
            'message' => [
                'data' => $this->faker->googleVoidedPurchaseNotification(),
            ],
        ];

        $response = $this->post('/liap/notifications?provider=google-play', $data);

        $response->assertStatus(200);
        Event::assertDispatchedTimes(GooglePlayNotificationReceivedEvent::class);
        Event::assertDispatched(
            GooglePlayNotificationReceivedEvent::class,
            static fn (GooglePlayNotificationReceivedEvent $event,
            ) => $event->payload->message->data === $data['message']['data']
        );
    }

    #[Test]
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
        Event::assertDispatched(
            GooglePlayNotificationReceivedEvent::class,
            static fn (GooglePlayNotificationReceivedEvent $event,
            ) => $event->payload->message->data === $data['message']['data']
        );
    }

    #[Test]
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
        Event::assertDispatched(
            GooglePlayNotificationReceivedEvent::class,
            static fn (
                GooglePlayNotificationReceivedEvent $event,
            ): bool => $event->payload->message->data === $data['message']['data']
        );
    }

    protected function tearDown(): void
    {
        $this->clearLogs();

        parent::tearDown();
    }
}
