<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Article>
 */
class ArticleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => fake()->sentence(), // Generates a random sentence for the title
            'source' => fake()->word(),    // Generates a random word for the source
            'content' => fake()->paragraph(), // Generates a random paragraph for the content
            'author' => fake()->name(),    // Generates a random name for the author
            'published_at' => fake()->dateTimeBetween('-1 year', 'now'), // Random date within the last year
            'category' => fake()->word(),   // Generates a random word for the category
        ];
    }
}
