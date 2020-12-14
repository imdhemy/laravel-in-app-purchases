<?php

namespace Imdhemy\Purchases\Tests\Http\Controllers;

use Illuminate\Support\Facades\Event;
use Imdhemy\Purchases\Events\AppStore\DidChangeRenewalStatus;
use Imdhemy\Purchases\Events\GooglePlay\SubscriptionPurchased;
use Imdhemy\Purchases\Tests\TestCase;

class ServerNotificationControllerTest extends TestCase
{
    /**
     * @test
     */
    public function test_google_subscription_notification()
    {
        Event::fake();
        $this->withoutExceptionHandling();
        $data = [
            'message' => [
                'data' => 'eyJ2ZXJzaW9uIjoiMS4wIiwicGFja2FnZU5hbWUiOiJjb20udHdpZ2Fuby5mYXNoaW9uIiwiZXZlbnRUaW1lTWlsbGlzIjoiMTYwMzMwMDgwNzM2MSIsInN1YnNjcmlwdGlvbk5vdGlmaWNhdGlvbiI6eyJ2ZXJzaW9uIjoiMS4wIiwibm90aWZpY2F0aW9uVHlwZSI6NCwicHVyY2hhc2VUb2tlbiI6ImFuZWZjcG1jamZib2RqbGNqZWVhY2piaC5BTy1KMU95NkxWQ2lJSkJBWUY4WVJCZklsaGZiSjlWTUJUamUybHo1bk1vSUV1SEdpMmdLVHczQXlZWEN4enhueGxKbWNOb0NEZlo2VnhFR05EQ0lLS1ZuVXZqUFZRODBPZyIsInN1YnNjcmlwdGlvbklkIjoid2Vla19wcmVtaXVtIn19',
            ],
        ];

        $uri = route('purchase.serverNotifications.google');

        $this->post($uri, $data)->assertStatus(200);

        Event::assertDispatched(SubscriptionPurchased::class);
    }

    /**
     * @test
     */
    public function test_google_test_notification()
    {
        file_put_contents(storage_path('logs/laravel.log'), "");
        $this->withoutExceptionHandling();
        $data = [
            'message' => [
                'data' => 'eyJ2ZXJzaW9uIjoiMS4wIiwicGFja2FnZU5hbWUiOiJjb20udHdpZ2Fuby5mYXNoaW9uIiwiZXZlbnRUaW1lTWlsbGlzIjoiMTYwMzkxNjUzMzcyMiIsInRlc3ROb3RpZmljYXRpb24iOnsidmVyc2lvbiI6IjEuMCJ9fQ==',
            ],
        ];
        $uri = route('purchase.serverNotifications.google');
        $this->post($uri, $data)->assertStatus(200);

        $this->assertTrue(
            ! empty(file_get_contents(storage_path("/logs/laravel.log")))
        );
    }

    /**
     * @test
     */
    public function test_app_store_server_notifications()
    {
        Event::fake();
        $this->withoutExceptionHandling();

        $data = json_decode(file_get_contents(__DIR__ . '/../../appstore-server-notification.json'), true);
        $uri = route('purchase.serverNotifications.apple');
        $this->post($uri, $data)->assertStatus(200);

        Event::assertDispatched(DidChangeRenewalStatus::class);
    }

    /**
     * @test
     */
    public function test_it_logs_the_weird_ZnNk_weird_token()
    {
        file_put_contents(storage_path('logs/laravel.log'), "");

        $data = json_decode(file_get_contents(__DIR__ . '/../../weird-token-from-google.json'), true);
        $uri = route('purchase.serverNotifications.google');
        $this->post($uri, $data)->assertStatus(200);

        $this->assertTrue(
            ! empty(file_get_contents(storage_path("/logs/laravel.log")))
        );
    }
}
