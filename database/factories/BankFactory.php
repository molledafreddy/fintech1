<?php

use Faker\Generator as Faker;

$factory->define(App\Bank::class, function (Faker $faker) {
    return [

        'name' => $faker->name,
        'number' => $faker->word,
        'transfer_key' => $faker->name,
    ];
});
