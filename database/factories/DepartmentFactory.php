<?php

namespace Database\Factories;

use App\Models\Department;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class DepartmentFactory extends Factory
{
    protected $model = Department::class;

    public function definition(): array
    {
        $name = $this->faker->words(2, true);

        return [
            'name_am' => '??? ' . $this->faker->word,
            'name_en' => Str::title($name),
            'slug' => Str::slug($name) . '-' . Str::lower(Str::random(4)),
            'sort_order' => $this->faker->numberBetween(1, 20),
            'is_active' => true,
        ];
    }
}
