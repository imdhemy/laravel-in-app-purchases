<?php


namespace Imdhemy\Purchases\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static \Imdhemy\Purchases\Product googlePlay()
 * @method static \Imdhemy\Purchases\Product appStore()
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
