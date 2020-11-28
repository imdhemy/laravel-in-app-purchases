<?php


namespace Imdhemy\Purchases\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static \Imdhemy\Purchases\Subscription googlePlay()
 * @method static \Imdhemy\Purchases\Subscription appStore()
 */
class Subscription extends Facade
{
    /**
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'subscription';
    }
}
