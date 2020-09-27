<?php


namespace Imdhemy\Purchases\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Imdhemy\Purchases\GooglePlay\Contracts\ResponseInterface;

/**
 * Class SubscriptionPurchase
 * @property string purchase_token
 * @property string platform
 * @property string kind
 * @property string item_id
 * @package Imdhemy\Purchases\Tests\Models
 * @mixin Builder
 */
class PurchaseLog extends Model
{
    /**
     * @param ResponseInterface $response
     * @return static
     */
    public static function fromResponse(ResponseInterface $response): self
    {
        $object = new self();

        $object->purchase_token = $response->getPurchaseToken();
        $object->platform = $response->getPlatform();
        $object->kind = $response->getKind();
        $object->item_id = $response->getItemId();

        return $object;
    }

    /**
     * @return bool
     */
    public function isUnique(): bool
    {
        $attributes = [
            'platform' => $this->platform,
            'purchase_token' => $this->purchase_token,
        ];

        return ! (bool)$this->where($attributes)->first();
    }
}
