<?php


namespace Imdhemy\Purchases\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Query\Builder;
use Imdhemy\Purchases\Exceptions\CouldNotCreateGoogleClient;
use Imdhemy\Purchases\Exceptions\CouldNotCreateSubscription;
use Imdhemy\Purchases\GooglePlay\Contracts\ResponseInterface;
use Imdhemy\Purchases\GooglePlay\Subscriptions\Response;
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
     * @var Response
     */
    protected $subscriptionResponse;

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
        $object->subscriptionResponse = $response;

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
     * @return Response
     * @throws CouldNotCreateGoogleClient
     * @throws CouldNotCreateSubscription
     */
    public function getSubscriptionResponse(): Response
    {
        if (is_null($this->subscriptionResponse)) {
            $this->subscriptionResponse = Subscription::check($this->item_id, $this->purchase_token)->getResponse();
        }
        return $this->subscriptionResponse;
    }

    /**
     * @param Response $subscriptionResponse
     */
    public function setSubscriptionResponse(Response $subscriptionResponse): void
    {
        $this->subscriptionResponse = $subscriptionResponse;
    }
}
