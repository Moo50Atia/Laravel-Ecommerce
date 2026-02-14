<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Blog>
 */
class BlogFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {

        $title = fake()->sentence();
        return [
            'title' => $title,
            'content' => fake()->paragraph(5),
            'author_id' => User::factory(),
            'short_description' => $this->faker->sentence(),
            'slug' => Str::slug($title),
            'is_published' => fake()->boolean(50),
            'published_at' => now(),
        ];
    }
}
