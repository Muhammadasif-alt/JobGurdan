<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Scholarship>
 */
class ScholarshipFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = Str::title(fake()->unique()->words(3, true)).' Scholarship';

        return [
            'title' => $title,
            'slug' => Str::slug($title),
            'provider' => fake()->lastName().' University',
            'country' => fake()->randomElement(['Canada', 'Germany', 'Ireland', 'Japan']),
            'city' => fake()->city(),
            'study_level' => fake()->randomElement(["Bachelor's", "Master's", 'PhD']),
            'funding_type' => fake()->randomElement(['Fully funded', 'Partial funding']),
            'award_value' => 'USD $'.number_format(fake()->numberBetween(5, 40) * 1000).' per year',
            'deadline' => fake()->dateTimeBetween('+1 month', '+6 months')->format('Y-m-d'),
            'deadline_note' => null,
            'excerpt' => fake()->sentence(16),
            'content' => '<p>'.fake()->paragraph().'</p>',
            'featured_image' => null,
            'apply_url' => fake()->url(),
            'meta_title' => null,
            'meta_description' => null,
            'status' => 'published',
            'is_featured' => false,
            'published_at' => now()->subDay(),
        ];
    }

    /** A scholarship still being written, which visitors must not see. */
    public function draft(): static
    {
        return $this->state(fn (array $attributes) => ['status' => 'draft']);
    }

    /** A published scholarship scheduled to appear later. */
    public function scheduled(): static
    {
        return $this->state(fn (array $attributes) => ['published_at' => now()->addWeek()]);
    }
}
