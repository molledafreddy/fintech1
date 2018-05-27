<?php

use Faker\Generator as Faker;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(App\User::class, function (Faker $faker) {
    static $password;

    return [
        'rfc' => $faker->postcode,
        'name' => $faker->name,
        'nacionality' => $faker->word,
        'email' => $faker->unique()->email,
        'password' => $password ?: $password = bcrypt('secret'),
        'role' => $faker -> randomElement($array = array ('administrador','empresa','empleado')),
        'remember_token' => str_random(10),
    ];
});
