<?php

namespace Imdhemy\Purchases;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Imdhemy\Purchases\Purchase
 */
class PurchaseFacade extends Facade
{
    /**
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'purchase';
    }
}
