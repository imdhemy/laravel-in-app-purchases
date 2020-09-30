<?php


namespace Imdhemy\Purchases\Tests\Models;

use Imdhemy\Purchases\Models\PurchaseLog;
use Imdhemy\Purchases\Tests\TestCase;

class FailedRenewalTest extends TestCase
{
    /**
     * @test
     */
    public function test_trials_can_be_incremented()
    {
        /** @var PurchaseLog $log */
        $log = factory(PurchaseLog::class)->create();
        $failedRenewal = $log->markAsFailedRenewal();
        $failedRenewal->incrementTrials();
        $this->assertDatabaseHas(
            'failed_renewals',
            [
            'id' => $failedRenewal->getId(),
            'trials' => 2,
            ]
        );
    }

    /**
     * @test
     */
    public function test_it_can_get_its_purchase_log_instance()
    {
        /** @var PurchaseLog $log */
        $log = factory(PurchaseLog::class)->create();
        $failedRenewal = $log->markAsFailedRenewal();
        $this->assertEquals($log->toArray(), $failedRenewal->getPurchase()->toArray());
    }
}
