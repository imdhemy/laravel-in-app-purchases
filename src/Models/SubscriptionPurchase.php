<?php


namespace Imdhemy\Purchases\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Imdhemy\Purchases\GooglePlay\Subscriptions\Response;

/**
 * Class SubscriptionPurchase
 * @property string purchase_token
 * @property int expiry_time
 * @property int start_time
 * @property int price_amount_micros
 * @property string price_currency_code
 * @package Imdhemy\Purchases\Tests\Models
 * @mixin Builder
 */
class SubscriptionPurchase extends Model
{
    /**
     * @param Response $response
     * @return static
     */
    public static function fromResponse(Response $response): self
    {
        $object = new self();

        $object->purchase_token = $response->getPurchaseToken();
        $object->expiry_time = $response->getExpiryTimeMillis();
        $object->start_time = $response->getStartTimeMillis();
        $object->price_amount_micros = $response->getPriceAmountMicros();
        $object->price_currency_code = $response->getPriceCurrencyCode();

        return $object;
    }
}
