<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use Faker\Generator as Faker;

$factory->define(App\InterviewTokens::class, function (Faker $faker) {
    return [
        'interview_id' => $faker->randomNumber(),
        'token_id' => $faker->randomNumber(),
        'valutation' => $faker->text,
        'sorting_id' => $faker->randomNumber(),
    ];
});
