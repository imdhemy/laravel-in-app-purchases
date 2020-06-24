<?php

namespace Imdhemy\Purchases\Tests\Validation;

use Imdhemy\Purchases\Tests\TestCase;
use Imdhemy\Purchases\Validation\SubscriptionValidator;

/**
 * Class SubscriptionValidatorTest
 * @package Imdhemy\Purchases\Tests\Validation
 */
class SubscriptionValidatorTest extends TestCase
{
    /**
     * @test
     */
    public function it_returns_true_with_a_valid_subscription()
    {
        $validator = new SubscriptionValidator();
    }
}
