<?php

declare(strict_types=1);

namespace Imdhemy\Purchases\Facades;

use GuzzleHttp\ClientInterface;
use Illuminate\Support\Facades\Facade;
use Override;

/**
 * @method static \Imdhemy\Purchases\Subscription googlePlay(?ClientInterface $client = null)
 * @method static \Imdhemy\Purchases\Subscription appStore(?ClientInterface $client = null)
 *
 * @psalm-api
 */
class Subscription extends Facade
{
    /**
     * @return string
     */
    #[Override]
    protected static function getFacadeAccessor()
    {
        return 'subscription';
    }
}
