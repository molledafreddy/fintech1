<?php

use Faker\Generator as Faker;

$factory->define(App\Account::class, function (Faker $faker) {
    return [
        //
        'number' => $faker->numberBetween($min = 10000000, $max = 99999999),
        'bank_id' => $faker->numberBetween($min = 1, $max = 10),
        'status' =>'disable',
        'daily_amount' => '50000000',        
    ];
});

            