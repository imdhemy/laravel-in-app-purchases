<?php

declare(strict_types=1);

namespace Imdhemy\Purchases\Tests\Feature;

use Illuminate\Support\Facades\Event;
use Imdhemy\Purchases\Events\AppStore\DidChangeRenewalStatus;
use Imdhemy\Purchases\Tests\TestCase;

final class HandleLegacyAppStoreNotificationFeatureTest extends TestCase
{
    /** @test */
    public function handle_app_store_server_notifications(): void
    {
        $this->withoutExceptionHandling();
        Event::fake();
        $data = json_decode(
            file_get_contents($this->fixturesDir('appstore-server-notification.json')),
            true,
            512,
            JSON_PARTIAL_OUTPUT_ON_ERROR
        );
        $this->post('/liap/notifications?provider=app-store', $data)->assertStatus(200);

        Event::assertDispatched(DidChangeRenewalStatus::class);
    }
}
