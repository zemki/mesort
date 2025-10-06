<?php

namespace Database\Factories;

use App\Sorting;
use App\Study;
use App\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class StudyFactory extends Factory
{
    protected $model = Study::class;

    public function definition(): array
    {
        return [
            'name' => fake()->company(),
            'display_name' => fake()->words(3, true),
            'user_id' => User::factory(),
            'author' => fake()->name(),
            'description' => fake()->paragraph(),
        ];
    }

    public function configure()
    {
        return $this->afterCreating(function (Study $study) {
            // Attach a default sorting if sortings exist (from seeder)
            $sorting = Sorting::first();
            if ($sorting) {
                $study->sortings()->attach($sorting->id, [
                    'details' => 'circles|5||description|Default study',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        });
    }
}
