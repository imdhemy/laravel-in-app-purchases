<?php

namespace Imdhemy\Purchases\Tests\Models;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Imdhemy\Purchases\Exceptions\CouldNotCreateGoogleClient;
use Imdhemy\Purchases\Exceptions\CouldNotCreateSubscription;
use Imdhemy\Purchases\GooglePlay\Products\Product;
use Imdhemy\Purchases\GooglePlay\Subscriptions\Subscription;
use Imdhemy\Purchases\Models\FailedRenewal;
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

    /**
     * @test
     */
    public function test_it_can_be_marked_as_failed_renewal()
    {
        /** @var PurchaseLog $log */
        $log = factory(PurchaseLog::class)->create();
        $failedRenewal = $log->markAsFailedRenewal();
        $this->assertInstanceOf(FailedRenewal::class, $failedRenewal);
        $this->assertDatabaseHas('failed_renewals', ['purchase_log_id' => $log->getId()]);
    }

    /**
     * @test
     */
    public function test_it_can_get_subscription_checker()
    {
        $id = 'week_premium';
        $token = 'cjlbcolbafbjjfapmdkilblj.AO-J1Ox5_dfU1L8iREhniLolz8oNoz3SRgi0NMGgkkmbbqvvk9dy2-E_AI02y1PAnYWTelRKdXQzMclHtaouAHZjb9ISWUIAjlEboIiu4pd84sQXvfsKuTjbWuT5r_v_ZphxrJcPypJs';
        /** @var PurchaseLog $log */
        $log = factory(PurchaseLog::class)->create([
            'item_id' => $id,
            'purchase_token' => $token,
        ]);
        $this->assertInstanceOf(Subscription::class, $log->getChecker());
    }

    /**
     * @test
     */
    public function test_it_can_be_checked_if_cancelled()
    {
        $id = 'week_premium';
        $token = 'cjlbcolbafbjjfapmdkilblj.AO-J1Ox5_dfU1L8iREhniLolz8oNoz3SRgi0NMGgkkmbbqvvk9dy2-E_AI02y1PAnYWTelRKdXQzMclHtaouAHZjb9ISWUIAjlEboIiu4pd84sQXvfsKuTjbWuT5r_v_ZphxrJcPypJs';
        /** @var PurchaseLog $log */
        $log = factory(PurchaseLog::class)->create([
            'item_id' => $id,
            'purchase_token' => $token,
        ]);
        $this->assertTrue($log->isCancelled());
    }

    /**
     * @test
     */
    public function test_it_can_check_if_it_has_a_valid_payment()
    {
        $id = 'week_premium';
        $token = 'cjlbcolbafbjjfapmdkilblj.AO-J1Ox5_dfU1L8iREhniLolz8oNoz3SRgi0NMGgkkmbbqvvk9dy2-E_AI02y1PAnYWTelRKdXQzMclHtaouAHZjb9ISWUIAjlEboIiu4pd84sQXvfsKuTjbWuT5r_v_ZphxrJcPypJs';
        /** @var PurchaseLog $log */
        $log = factory(PurchaseLog::class)->create([
            'item_id' => $id,
            'purchase_token' => $token,
        ]);
        $this->assertFalse($log->isValidPayment());
    }
}
