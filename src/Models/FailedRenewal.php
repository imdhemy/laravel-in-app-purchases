<?php


namespace Imdhemy\Purchases\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Ramsey\Uuid\Uuid;

/**
 * @property string id
 * @property int trials
 * @property PurchaseLog purchase
 */
class FailedRenewal extends Model
{
    /**
     * @var string
     */
    protected $table = 'failed_renewals';

    /**
     * @var array
     */
    protected $guarded = [];

    /**
     * @var string
     */
    protected $keyType = 'string';

    /**
     * @var bool
     */
    public $incrementing = false;

    /**
     * @var array
     */
    protected $casts = [
        'id' => 'string',
        'updated_at' => 'datetime',
        'created_at' => 'datetime',
    ];

    /**
     * Bootstrap the model and its traits.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function (Model $model) {
            $model->setAttribute($model->getKeyName(), Uuid::uuid4());
        });
    }

    /**
     * @return $this
     */
    public function incrementTrials(): self
    {
        $this->increment('trials');

        return $this;
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @return int
     */
    public function getTrials(): int
    {
        return $this->trials;
    }

    /**
     * @return BelongsTo
     */
    public function purchase()
    {
        return $this->belongsTo(PurchaseLog::class, 'purchase_log_id');
    }

    /**
     * @return PurchaseLog
     */
    public function getPurchase(): PurchaseLog
    {
        return $this->purchase;
    }
}
