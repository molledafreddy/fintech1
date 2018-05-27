<?php

use Faker\Generator as Faker;

$factory->define(App\Order::class, function (Faker $faker) {
    return [
        'customer_user_id' => function(){
          return factory(App\CustomerUser::class)->create()->id;
        },
        'amount' => $faker->numberBetween($min = 1, $max = 9999999),
    ];
});
