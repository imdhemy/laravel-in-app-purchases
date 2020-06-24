<?php

use Faker\Generator;
use Imdhemy\Purchases\Models\SubscriptionPurchase;

/* @var Illuminate\Database\Eloquent\Factory $factory */
$factory->define(SubscriptionPurchase::class, function (Generator $faker) {
    return [
        'purchase_token' => bcrypt($faker->unique()->password),
        'expiry_time' => $faker->unixTime,
        'start_time' => $faker->unixTime,
        'price_amount_micros' => $faker->randomNumber(2),
        'price_currency_code' => $faker->currencyCode,
    ];
});
