<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use Faker\Generator as Faker;

$factory->define(App\Study::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'display_name' => $faker->word,
        'user_id' => $faker->randomNumber(),
        'author' => $faker->text,
        'description' => $faker->text,
    ];
});
