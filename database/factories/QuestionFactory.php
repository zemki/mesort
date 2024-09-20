<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use Faker\Generator as Faker;

$factory->define(App\Question::class, function (Faker $faker) {
    return [
        'question' => $faker->word,
        'detail' => $faker->word,
        'study_id' => $faker->randomNumber(),
    ];
});
