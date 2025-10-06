<?php

namespace Database\Factories;

use App\Interview;
use App\Study;
use App\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class InterviewFactory extends Factory
{
    protected $model = Interview::class;

    public function definition(): array
    {
        return [
            'author' => 1,  // Will be overridden in tests
            'interviewed' => fake()->name(),
            'study_id' => Study::factory(),
            'start' => fake()->dateTime(),
            'end' => fake()->dateTime(),
        ];
    }
}
