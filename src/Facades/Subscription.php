<?php


namespace Imdhemy\Purchases\Facades;


use Illuminate\Support\Facades\Facade;

/**
 * @method static \Imdhemy\Purchases\Subscription googlePlay()
 */
class Subscription extends Facade
{
    /**
     * @return string|void
     */
    protected static function getFacadeAccessor()
    {
        return 'subscription';
    }
}

