<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Homepage>
 */
class HomepageFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->word(),
            'path' => fake()->filePath(),
            'type' => fake()->word(),
            'structure' => [
                'title' => fake()->sentence(),
                'author' => fake()->name(),
                'created_at' => fake()->dateTime()->format('Y-m-d H:i:s'),
                'flags' => [
                    'featured' => fake()->boolean(),
                    'archived' => fake()->boolean(),
                ],
                'tags' => fake()->words(5),
            ],

        ];
    }
}
