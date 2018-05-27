<?php

use Faker\Generator as Faker;

$factory->define(App\Credit::class, function (Faker $faker) {
    return [
        'order_id' => $faker->numberBetween($min = 1, $max = 10),
        'status' =>  $faker -> randomElement($array = array ('pending_processing', 'processing','ready','error')),
    ];
});
