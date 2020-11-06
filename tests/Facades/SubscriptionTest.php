<?php

namespace Imdhemy\Purchases\Tests\Facades;

use Imdhemy\GooglePlay\Subscriptions\SubscriptionPurchase;
use Imdhemy\Purchases\Facades\Subscription;
use Imdhemy\Purchases\Tests\TestCase;

class SubscriptionTest extends TestCase
{
    /**
     * @var string
     */
    private $token;

    /**
     * @var string
     */
    private $itemId;

    /**
     * @inheritDoc
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->token = 'hhblgkiaeomlogloocbpnkce.AO-J1OzV6UAh0NbPTYq92zJ-P3zhsgc1whL3RiEM1WOlWEUjuypjlIHDRsL8lzAXW7DcSuD2Zr-lDLar9S8Clbsq25bqhf7MHQ';
        $this->itemId = 'month_premium';
    }

    /**
     * @test
     */
    public function test_facade_can_get_a_google_play_subscription()
    {
        $this->assertInstanceOf(
            SubscriptionPurchase::class,
            Subscription::googlePlay()->packageName('com.twigano.fashion')->id($this->itemId)->token($this->token)->get()
        );
    }

    /**
     * @test
     */
    public function test_facade_can_acknowledge_a_google_play_subscription()
    {
        $this->assertNull(
            Subscription::googlePlay()->packageName('com.twigano.fashion')->id($this->itemId)->token($this->token)->acknowledge()
        );
    }
}
