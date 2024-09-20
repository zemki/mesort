<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use Faker\Generator as Faker;

$factory->define(App\Action::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'description' => $faker->text,
        'url' => $faker->url,
        'user_id' => function () {
            return factory(App\User::class)->create()->id;
        },
        'time' => $faker->dateTime(),
    ];
});
