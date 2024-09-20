<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use Faker\Generator as Faker;

$factory->define(App\Answer::class, function (Faker $faker) {
    return [
        'question_id' => $faker->randomNumber(),
        'answer' => $faker->text,
    ];
});
