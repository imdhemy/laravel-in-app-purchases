<?php

namespace Imdhemy\Purchases\Tests\Events;

use Imdhemy\GooglePlay\DeveloperNotifications\DeveloperNotification;
use Imdhemy\GooglePlay\Subscriptions\SubscriptionPurchase;
use Imdhemy\Purchases\Events\GooglePlay\SubscriptionRenewed;
use Imdhemy\Purchases\Events\PurchaseEvent;
use Imdhemy\Purchases\Tests\TestCase;

class PurchaseEventTest extends TestCase
{
    /**
     * @var PurchaseEvent
     */
    private $event;

    /**
     * @var DeveloperNotification
     */
    private $developerNotification;

    /**
     * @inheritDoc
     */
    public function setUp(): void
    {
        parent::setUp();
        $data = 'eyJ2ZXJzaW9uIjoiMS4wIiwicGFja2FnZU5hbWUiOiJjb20udHdpZ2Fuby5mYXNoaW9uIiwiZXZlbnRUaW1lTWlsbGlzIjoiMTYwMzMwMDgwNzM2MSIsInN1YnNjcmlwdGlvbk5vdGlmaWNhdGlvbiI6eyJ2ZXJzaW9uIjoiMS4wIiwibm90aWZpY2F0aW9uVHlwZSI6NCwicHVyY2hhc2VUb2tlbiI6ImFuZWZjcG1jamZib2RqbGNqZWVhY2piaC5BTy1KMU95NkxWQ2lJSkJBWUY4WVJCZklsaGZiSjlWTUJUamUybHo1bk1vSUV1SEdpMmdLVHczQXlZWEN4enhueGxKbWNOb0NEZlo2VnhFR05EQ0lLS1ZuVXZqUFZRODBPZyIsInN1YnNjcmlwdGlvbklkIjoid2Vla19wcmVtaXVtIn19';
        $this->developerNotification = DeveloperNotification::parse($data);
        $this->event = new SubscriptionRenewed($this->developerNotification);
    }

    /**
     * @test
     */
    public function test_it_can_get_developer_notification()
    {
        $this->assertSame($this->developerNotification, $this->event->getDeveloperNotification());
    }

    /**
     * @test
     */
    public function test_it_can_get_subscription_notification()
    {
        $this->assertEquals(
            $this->developerNotification->getSubscriptionNotification(),
            $this->event->getSubscriptionNotification()
        );
    }

    /**
     * @test
     */
    public function test_it_can_get_subscription_purchase_get_resource_from_google_developer_api()
    {
        $this->assertInstanceOf(SubscriptionPurchase::class, $this->event->getSubscriptionPurchase());
    }
}
