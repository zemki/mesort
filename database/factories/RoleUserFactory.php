<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use Faker\Generator as Faker;

$factory->define(App\RoleUser::class, function (Faker $faker) {
    return [
        'role_id' => $faker->randomNumber(),
        'user_id' => $faker->randomNumber(),
        'user_type' => $faker->word,
        'study_id' => $faker->randomNumber(),
    ];
});
