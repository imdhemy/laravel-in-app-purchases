<?php


namespace Imdhemy\Purchases\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static \Imdhemy\Purchases\Product googlePlay()
 */
class Product extends Facade
{
    /**
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'product';
    }
}
