<?php

use Faker\Generator;
use Illuminate\Support\Str;
use Imdhemy\Purchases\Models\PurchaseLog;

/* @var Illuminate\Database\Eloquent\Factory $factory */
$factory->define(PurchaseLog::class, function (Generator $faker) {
    return [
        'purchase_token' => bcrypt($faker->unique()->password),
        'platform' => 'google_play',
        'kind' => 'google_play#' .Str::random(),
    ];
});
