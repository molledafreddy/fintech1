<?php

use Faker\Generator as Faker;

$factory->define(App\Customer::class, function (Faker $faker) {
    return [
        //
        'name'    => $faker->company,
       	'address' => $faker->address,
       	'phone'   => $faker->phoneNumber,
       	'web_site' => $faker->word,
       	'rfc' => $faker->randomNumber(),
       	'city' => $faker->country,
       	'cp_first_name' => $faker->name,
       	'cp_last_name' => $faker->lastName,
       	'cp_email' => $faker->email,
       	'cp_phone' => $faker->phoneNumber,
       	'active' => $faker->boolean($chanceOfGettingTrue = 10),
        'admin_id' => function(){
          return factory(App\User::class)->create()->id;
        },
       	'status' =>  $faker ->randomElement($array = array ('pendiente por verificar','verificado','inactivo')),
    ];
});
