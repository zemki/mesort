<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use Faker\Generator as Faker;

$factory->define(App\Interview::class, function (Faker $faker) {
    return [
        'author' => $faker->word,
        'interviewed' => $faker->word,
        'study_id' => function () {
            return factory(App\Study::class)->create()->id;
        },
        'start' => $faker->dateTime(),
        'end' => $faker->dateTime(),
        'author_id' => function () {
            return factory(App\User::class)->create()->id;
        },
    ];
});
