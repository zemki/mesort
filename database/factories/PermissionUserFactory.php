<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use Faker\Generator as Faker;

$factory->define(App\PermissionUser::class, function (Faker $faker) {
    return [
        'permission_id' => $faker->randomNumber(),
        'user_id' => $faker->randomNumber(),
        'user_type' => $faker->word,
        'study_id' => $faker->randomNumber(),
    ];
});
