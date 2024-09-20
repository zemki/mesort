<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use Faker\Generator as Faker;

$factory->define(App\Profile::class, function (Faker $faker) {
    return [
        'user_id' => function () {
            return factory(App\User::class)->create()->id;
        },
        'name' => $faker->name,
        'address' => $faker->word,
        'birthday' => $faker->date(),
        'phonenumber1' => $faker->word,
        'phonenumber2' => $faker->word,
        'workaddress' => $faker->word,
    ];
});
