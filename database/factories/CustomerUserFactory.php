<?php

use Faker\Generator as Faker;

$factory->define(App\CustomerUser::class, function (Faker $faker) {
    return [
        'customer_id' => function(){
          return factory(App\Customer::class)->create()->id;
        } ,
        'user_id' => function(){
          return factory(App\User::class)->create()->id;
        },
        'bank_id' => function(){
          return factory(App\Bank::class)->create()->id;
        },
       	'phone'   => $faker->phoneNumber,
        'biweekly_salary' => $faker ->randomFloat($nbMaxDecimals = 2, $min = 1, $max = 600000),
       	'acconunt_number' => $faker ->bankAccountNumber,
       	'acconunt_clabe' => $faker ->numberBetween($min = 100, $max = 999),
       	'status' =>  $faker -> randomElement($array = array ('pending','verifying', 'ready','error')), 
       	'acceptance_terms' => 'no',
        'file_id' => function(){
          return factory(App\File::class)->create()->id;
        },
    ];
});
