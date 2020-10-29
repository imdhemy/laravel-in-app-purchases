<?php

namespace Imdhemy\Purchases\Tests\Http\Controllers;

use Illuminate\Support\Facades\Event;
use Imdhemy\Purchases\Events\GooglePlay\SubscriptionExpired;
use Imdhemy\Purchases\Events\GooglePlay\SubscriptionPurchased;
use Imdhemy\Purchases\Tests\TestCase;

class DeveloperNotificationControllerTest extends TestCase
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
        $uri = route('purchase.developerNotifications.google');
        $this->post($uri, $data)->assertStatus(200);

        Event::assertDispatched(SubscriptionPurchased::class);
    }

    /**
     * @test
     */
    public function test_google_subscription_expired_notification()
    {
        Event::fake();
        $this->withoutExceptionHandling();
        $data = [
            'message' => [
                'data' => 'eyJ2ZXJzaW9uIjoiMS4wIiwicGFja2FnZU5hbWUiOiJjb20udHdpZ2Fuby5mYXNoaW9uIiwiZXZlbnRUaW1lTWlsbGlzIjoiMTYwNDAwMjg0MjMzMiIsInN1YnNjcmlwdGlvbk5vdGlmaWNhdGlvbiI6eyJ2ZXJzaW9uIjoiMS4wIiwibm90aWZpY2F0aW9uVHlwZSI6MTMsInB1cmNoYXNlVG9rZW4iOiJuYWRpZmJwZWtmZmRjYmNvZGdwa2NrYWMuQU8tSjFPekxnU0ZQSWFESmVKTVF4dnZIYnBMeTlLZ3dYbHVyQjk1UlBINHFZdGYxSmdzV1B3NHV4bmlkYUlmeGJreXVpTDVOZ3ZudVU3TEpvNzIzeHpfVVRhUEZXc0YyZEEiLCJzdWJzY3JpcHRpb25JZCI6Im1vbnRoX3ByZW1pdW0ifX0=',
            ],
        ];
        $uri = route('purchase.developerNotifications.google');
        $this->post($uri, $data)->assertStatus(200);

        Event::assertDispatched(SubscriptionExpired::class);
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
        $uri = route('purchase.developerNotifications.google');
        $this->post($uri, $data)->assertStatus(200);

        $this->assertTrue(
            !empty(file_get_contents(storage_path("/logs/laravel.log")))
        );
    }
}
