<?php

namespace Database\Factories;

use App\Models\DocumentCategory;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class DocumentCategoryFactory extends Factory
{
    protected $model = DocumentCategory::class;

    public function definition(): array
    {
        $name = $this->faker->words(2, true);
        $nameAm = $this->faker->words(2, true);

        return [
            'name' => Str::title($name),
            'name_am' => Str::title($nameAm),
            'slug' => Str::slug($name) . '-' . Str::lower(Str::random(4)),
            'sort_order' => $this->faker->numberBetween(1, 20),
            'is_active' => true,
        ];
    }
}
