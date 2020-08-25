<?php


namespace Imdhemy\Purchases\Tests\GooglePlay;

use Imdhemy\Purchases\Exceptions\CouldNotCreateGoogleClient;
use Imdhemy\Purchases\GooglePlay\Contracts\ResponseInterface;
use Imdhemy\Purchases\GooglePlay\Product\Product;
use Imdhemy\Purchases\Tests\TestCase;

/**
 * Class ProductTest
 * @package Imdhemy\Purchases\Tests\GooglePlay
 */
class ProductTest extends TestCase
{
    /**
     * @var string
     */
    private $productId;

    /**
     * @var string
     */
    private $purchaseToken;

    public function setUp(): void
    {
        parent::setUp();
        $this->productId = 'boost_profile';
        $this->purchaseToken = 'ghopjnbcmgknkbhpdpeccobn.AO-J1OxlcL7ASplV31Zh9DdPQJl0tH56dB4SshdzHJ0EvsmQDG5Uef8irDRX82hDJs7LDtDIt7Y2yoam_j70WhvV9B0aebmb0MOqbRsOvtX7L_KK8vS6BasGLUkZnVwd29C3rm9sBma0';
    }

    /**
     * @test
     * @throws CouldNotCreateGoogleClient
     */
    public function test_it_get_receipt_validation_response()
    {
        $this->assertInstanceOf(
            ResponseInterface::class,
            Product::check($this->productId, $this->purchaseToken)->getResponse()
        );
    }

    /**
     * @test
     * @throws CouldNotCreateGoogleClient
     */
    public function test_it_can_validate_a_receipt()
    {
        $receipt = Product::check($this->productId, $this->purchaseToken);
        $this->assertFalse($receipt->isValid());
    }
}
