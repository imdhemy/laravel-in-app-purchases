<?php

namespace Imdhemy\Purchases\Contracts;

interface HasSubtype
{
    /**
     * Gets subscription subtype
     *
     * @return string
     */
    public function getSubtype(): string;
}
