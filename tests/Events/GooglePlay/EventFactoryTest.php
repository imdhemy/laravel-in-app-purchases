<?php

namespace Tests\Events\GooglePlay;

use Imdhemy\GooglePlay\DeveloperNotifications\DeveloperNotification;
use Imdhemy\Purchases\Events\GooglePlay\EventFactory;
use Imdhemy\Purchases\Events\GooglePlay\SubscriptionPurchased;
use Imdhemy\Purchases\ServerNotifications\GoogleServerNotification;
use Tests\TestCase;

class EventFactoryTest extends TestCase
{
    /**
     * @test
     */
    public function test_create()
    {
        $data = 'eyJ2ZXJzaW9uIjoiMS4wIiwicGFja2FnZU5hbWUiOiJjb20udHdpZ2Fuby5mYXNoaW9uIiwiZXZlbnRUaW1lTWlsbGlzIjoiMTYwMzMwMDgwNzM2MSIsInN1YnNjcmlwdGlvbk5vdGlmaWNhdGlvbiI6eyJ2ZXJzaW9uIjoiMS4wIiwibm90aWZpY2F0aW9uVHlwZSI6NCwicHVyY2hhc2VUb2tlbiI6ImFuZWZjcG1jamZib2RqbGNqZWVhY2piaC5BTy1KMU95NkxWQ2lJSkJBWUY4WVJCZklsaGZiSjlWTUJUamUybHo1bk1vSUV1SEdpMmdLVHczQXlZWEN4enhueGxKbWNOb0NEZlo2VnhFR05EQ0lLS1ZuVXZqUFZRODBPZyIsInN1YnNjcmlwdGlvbklkIjoid2Vla19wcmVtaXVtIn19';
        $developerNotification = DeveloperNotification::parse($data);
        $googleServerNotification = new GoogleServerNotification($developerNotification);
        $event = EventFactory::create($googleServerNotification);
        $this->assertInstanceOf(SubscriptionPurchased::class, $event);
    }
}
