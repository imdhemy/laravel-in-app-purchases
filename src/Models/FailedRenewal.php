<?php


namespace Imdhemy\Purchases\Models;

use Illuminate\Database\Eloquent\Model;
use Ramsey\Uuid\Uuid;

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
}
