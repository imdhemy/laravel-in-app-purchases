<?php


namespace Imdhemy\Purchases\Models;

use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Query\Builder;
use Imdhemy\Purchases\Exceptions\CouldNotCreateGoogleClient;
use Imdhemy\Purchases\Exceptions\CouldNotCreateSubscription;
use Imdhemy\Purchases\GooglePlay\Contracts\ResponseInterface;
use Imdhemy\Purchases\GooglePlay\Subscriptions\Subscription;

/**
 * Class SubscriptionPurchase
 * @property string purchase_token
 * @property string platform
 * @property string kind
 * @property string item_id
 * @property int id
 * @mixin Builder
 */
class PurchaseLog extends Model
{
    /**
     * @var Subscription
     */
    protected $subscriptionChecker;

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

    /**
     * @return Subscription
     * @throws CouldNotCreateGoogleClient
     * @throws CouldNotCreateSubscription
     */
    public function getChecker(): Subscription
    {
        if (is_null($this->subscriptionChecker)) {
            $this->subscriptionChecker = Subscription::check($this->item_id, $this->purchase_token);
        }

        return $this->subscriptionChecker;
    }

    /**
     * @return bool
     */
    public function isCancelled(): bool
    {
        try {
            $checker = $this->getChecker();

            return $checker->isCancelled();
        } catch (Exception $exception) {
            return true;
        }
    }

    /**
     * @return bool
     */
    public function isValidPayment(): bool
    {
        try {
            $checker = $this->getChecker();

            return $checker->isValidPayment();
        } catch (Exception $exception) {
            return false;
        }
    }
}
