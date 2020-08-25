<?php

namespace Imdhemy\Purchases\GooglePlay\Contracts;

use Imdhemy\Purchases\GooglePlay\Subscriptions\Response;

/**
 * Interface CheckerInterface
 * @package Imdhemy\Purchases\GooglePlay\Contracts
 */
interface CheckerInterface
{
    /**
     * @return Response
     */
    public function getResponse(): Response;

    /**
     * @return bool
     */
    public function isValid(): bool;
}
