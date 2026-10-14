<?php

declare(strict_types=1);

namespace Imdhemy\Purchases\Contracts;

/**
 * @psalm-api
 */
interface HasSubtype
{
    /**
     * Gets subscription subtype.
     */
    public function getSubtype(): string;
}
