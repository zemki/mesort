<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use Faker\Generator as Faker;

$factory->define(App\Files::class, function (Faker $faker) {
    return [
        'type' => $faker->word,
        'path' => $faker->text,
        'size' => $faker->word,
        'interview_id' => function () {
            return factory(App\Interview::class)->create()->id;
        },
    ];
});
