<?php

use Faker\Generator as Faker;

$factory->define(App\File::class, function (Faker $faker) {
    return [
        //
        'name' => $faker->name,
        'status' =>'no_processed',
        'user_id' => function(){
            return factory(App\User::class)->create()->id;
        } ,
    ];
});
