<?php

namespace Database\Factories;

use App\Models\Service;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ServiceFactory extends Factory
{
    protected $model = Service::class;

    public function definition(): array
    {
        $title = $this->faker->sentence(5);
        $slug = Str::slug($title) . '-' . Str::lower(Str::random(6));

        return [
            'title' => $title,
            'title_am' => $title . ' (AM)',
            'title_en' => $title,
            'slug' => $slug,
            'description' => $this->faker->paragraph,
            'description_am' => $this->faker->paragraph . ' (AM)',
            'description_en' => $this->faker->paragraph,
            'requirements' => $this->faker->sentence(10),
            'requirements_am' => $this->faker->sentence(10) . ' (AM)',
            'requirements_en' => $this->faker->sentence(10),
            'is_active' => true,
            'sort_order' => 1,
        ];
    }

    public function inactive(): static
    {
        return $this->state(['is_active' => false]);
    }
}
