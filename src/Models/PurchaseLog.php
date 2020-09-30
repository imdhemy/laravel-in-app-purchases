<?php


namespace Imdhemy\Purchases\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Query\Builder;
use Imdhemy\Purchases\GooglePlay\Contracts\ResponseInterface;

/**
 * Class SubscriptionPurchase
 * @property string purchase_token
 * @property string platform
 * @property string kind
 * @property string item_id
 * @property int id
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

    /**
     * @return HasOne
     */
    public function failedRenewal()
    {
        return $this->hasOne(FailedRenewal::class, 'purchase_log_id');
    }

    /**
     * @return FailedRenewal|Model
     */
    public function markAsFailedRenewal(): FailedRenewal
    {
        return $this->failedRenewal()->firstOrCreate([]);
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }
}
