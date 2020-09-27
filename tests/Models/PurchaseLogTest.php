<?php

namespace Imdhemy\Purchases\Tests\Models;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Imdhemy\Purchases\Exceptions\CouldNotCreateGoogleClient;
use Imdhemy\Purchases\Exceptions\CouldNotCreateSubscription;
use Imdhemy\Purchases\GooglePlay\Products\Product;
use Imdhemy\Purchases\GooglePlay\Subscriptions\Subscription;
use Imdhemy\Purchases\Models\PurchaseLog;
use Imdhemy\Purchases\Tests\TestCase;

/**
 * Class PurchaseLogTest
 * @package Imdhemy\Purchases\Tests\Models
 */
class PurchaseLogTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     * @throws CouldNotCreateGoogleClient
     * @throws CouldNotCreateSubscription
     */
    public function it_can_be_created_from_response()
    {
        $id = 'week_premium';
        $token = 'cjlbcolbafbjjfapmdkilblj.AO-J1Ox5_dfU1L8iREhniLolz8oNoz3SRgi0NMGgkkmbbqvvk9dy2-E_AI02y1PAnYWTelRKdXQzMclHtaouAHZjb9ISWUIAjlEboIiu4pd84sQXvfsKuTjbWuT5r_v_ZphxrJcPypJs';
        $receiptResponse = Subscription::check($id, $token)->getResponse();
        $this->assertInstanceOf(PurchaseLog::class, PurchaseLog::fromResponse($receiptResponse));
    }

    /**
     * @test
     * @throws CouldNotCreateGoogleClient
     */
    public function it_can_be_persisted_if_created_from_response()
    {
        $id = 'boost_profile';
        $token = 'noeafijodokmlmdmagkibkec.AO-J1Ox_sTeagnkp4UkS0TmujEZRculYJJVA9lPac68iUHrI6JC5ItrOPSUDHuMTOOHF5HT7kk4G7C79XB7r3lqIn6edYHGPNA5bv8tqs3Z-0fYoDZMKiDNIcKmZ93w-zEun8PJOGJzH';
        $receiptResponse = Product::check($id, $token)->getResponse();
        $log = PurchaseLog::fromResponse($receiptResponse);
        $log->save();
        
        $this->assertDatabaseHas('purchase_logs', [
            'purchase_token' => $token,
            'item_id' => $id,
        ]);
    }
}
