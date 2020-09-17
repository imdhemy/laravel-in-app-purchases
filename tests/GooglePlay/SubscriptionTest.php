<?php

namespace Imdhemy\Purchases\Tests\GooglePlay;

use Exception;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Imdhemy\Purchases\Exceptions\CouldNotCreateGoogleClient;
use Imdhemy\Purchases\Exceptions\CouldNotCreateSubscription;
use Imdhemy\Purchases\Exceptions\CouldNotPersist;
use Imdhemy\Purchases\GooglePlay\Subscriptions\Response;
use Imdhemy\Purchases\GooglePlay\Subscriptions\Subscription;
use Imdhemy\Purchases\Models\PurchaseLog;
use Imdhemy\Purchases\Tests\TestCase;

/**
 * Class SubscriptionTest
 * @package Imdhemy\Purchases\Tests\GooglePlay
 */
class SubscriptionTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @var string
     */
    private $id;

    /**
     * @var string
     */
    private $token;

    public function setUp(): void
    {
        parent::setUp();

        $this->id = self::SUBSCRIPTION_ID;
        $this->token = self::SUBSCRIPTION_PURCHASE_TOKEN;
    }

    /**
     * @test
     * @throws CouldNotCreateSubscription
     * @throws CouldNotCreateGoogleClient
     */
    public function it_can_get_subscription_response()
    {
        $response = Subscription::check($this->id, $this->token)->getResponse();
        $this->assertInstanceOf(Response::class, $response);
    }

    /**
     * @test
     * @throws CouldNotCreateSubscription
     * @throws CouldNotCreateGoogleClient
     */
    public function it_can_create_subscription_purchase()
    {
        $token = 'ohdmoncgapnhhmbhcbaofcig.AO-J1OyxlwZ8_pCxxfTSihyWiy32W0Bpc857ulFKaFORzkjYzdVarDWB88lohL_ZvetMBJYrDavB3iuVXvqvkBAiMN8LIV60P21ZOtwsUM7iGfdUVXSEEHTfjbuUnkrU1cHMLcF-lYGs';
        $purchase = Subscription::check($this->id, $token)->toLog();
        $this->assertInstanceOf(PurchaseLog::class, $purchase);
    }

    /**
     * @test
     * @throws CouldNotCreateSubscription
     * @throws CouldNotCreateGoogleClient
     */
    public function it_returns_true_if_it_was_unique()
    {
        $isUnique = Subscription::check($this->id, $this->token)->isUnique();
        $this->assertTrue($isUnique);
    }

    /**
     * @test
     * @throws CouldNotCreateSubscription
     * @throws CouldNotCreateGoogleClient
     */
    public function it_returns_false_if_it_was_not_unique()
    {
        factory(PurchaseLog::class)->create([
            'purchase_token' => $this->token,
        ]);

        $isUnique = Subscription::check($this->id, $this->token)->isUnique();
        $this->assertFalse($isUnique);
    }

    /**
     * @test
     * @throws CouldNotCreateSubscription
     * @throws CouldNotCreateGoogleClient
     * @throws CouldNotPersist
     */
    public function it_can_persisted_if_is_unique()
    {
        $purchase = Subscription::check($this->id, $this->token)->persist();
        $this->assertInstanceOf(PurchaseLog::class, $purchase);
        $this->assertDatabaseHas('purchase_logs', [
            'purchase_token' => $this->token,
        ]);
    }

    /**
     * @test
     * @throws CouldNotCreateSubscription
     * @throws CouldNotCreateGoogleClient
     * @throws CouldNotPersist
     */
    public function it_throws_exception_if_it_was_not_unique_on_persist()
    {
        $this->expectException(CouldNotPersist::class);
        factory(PurchaseLog::class)->create([
            'purchase_token' => $this->token,
        ]);
        Subscription::check($this->id, $this->token)->persist();
    }

    /**
     * @test
     * @throws CouldNotCreateSubscription
     * @throws CouldNotCreateGoogleClient
     */
    public function it_can_validate_a_purchase_receipt()
    {
        $token = 'kemninhggjhpklgdkpfkgbmg.AO-J1OxL5tWGJRCkFq_VE2n32A4Dsby62J3B1kjbrBnM-2b3E39DabaNaC2PnCv5oRgQDeqlzvGj_8KBUPpawWwhCohQpKYYCtVeM58Tm01TiKu2mDPY-P6lm24QfIm37UWV-W5RKuJ7';
        $isValid = Subscription::check($this->id, $token)->isValid();
        $this->assertTrue($isValid);
    }

    /**
     * @test
     * @throws CouldNotCreateGoogleClient
     * @throws CouldNotCreateSubscription
     */
    public function test_it_checks_testing_card_usage()
    {
        $token = 'ohdmoncgapnhhmbhcbaofcig.AO-J1OyxlwZ8_pCxxfTSihyWiy32W0Bpc857ulFKaFORzkjYzdVarDWB88lohL_ZvetMBJYrDavB3iuVXvqvkBAiMN8LIV60P21ZOtwsUM7iGfdUVXSEEHTfjbuUnkrU1cHMLcF-lYGs';
        $id = 'week_premium';

        $receipt = Subscription::check($id, $token);
        $this->assertTrue($receipt->isTesting());
    }

    /**
     * @test
     * @throws Exception
     */
    public function test_if_auto_renewal_is_activated()
    {
        $token = 'mjphmgahhhffogelhpiecaif.AO-J1Ox4F1SLBZMYBZHvJPZBpWV5Tbfq1AUewVU5rKidbbTsHLlqsCTwrpU59nHqhqeLupQaPD_jHCal0IHXhg_XJD4xsLubQ6NVSSmOXEoCqq1YDd01opmdhl0gGaOLL-GoQ2DohGTz';
        $id = 'week_premium';

        $receipt = Subscription::check($id, $token);
        $this->assertTrue($receipt->isAutoRenewing());
    }
}
