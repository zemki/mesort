<?php

namespace Database\Factories;

use App\Token;
use Illuminate\Database\Eloquent\Factories\Factory;

class TokenFactory extends Factory
{
    protected $model = Token::class;

    public function definition(): array
    {
        return [
            'name' => fake()->words(2, true),
            'image_path' => 'presets/' . fake()->numberBetween(1, 10) . '.png',
            'author' => 1,
            'properties' => json_encode(['description' => fake()->sentence()]),
        ];
    }
}
