<?php

namespace Database\Factories;

use App\Models\Post;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class PostFactory extends Factory
{
    protected $model = Post::class;

    public function definition(): array
    {
        $title = $this->faker->sentence(6);
        $slug = Str::slug($title) . '-' . Str::lower(Str::random(6));

        return [
            'title' => $title,
            'title_am' => $title . ' (AM)',
            'title_en' => $title,
            'slug' => $slug,
            'body' => $this->faker->paragraphs(3, true),
            'body_am' => $this->faker->paragraphs(3, true) . ' (AM)',
            'body_en' => $this->faker->paragraphs(3, true),
            'excerpt' => $this->faker->sentence(12),
            'excerpt_am' => $this->faker->sentence(12) . ' (AM)',
            'excerpt_en' => $this->faker->sentence(12),
            'type' => 'news',
            'is_published' => true,
            'published_at' => now()->subDay(),
        ];
    }

    public function announcement(): static
    {
        return $this->state(['type' => 'announcement']);
    }

    public function draft(): static
    {
        return $this->state([
            'is_published' => false,
            'published_at' => null,
        ]);
    }
}
