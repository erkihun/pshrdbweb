<?php

namespace Database\Factories;

use App\Models\Page;
use Illuminate\Database\Eloquent\Factories\Factory;

class PageFactory extends Factory
{
    protected $model = Page::class;

    public function definition(): array
    {
        return [
            'key' => $this->faker->unique()->slug(2),
            'title_am' => '???? ??? ' . $this->faker->word,
            'title_en' => $this->faker->sentence(4),
            'body_am' => '?? ????? ?? ???? ??? ' . $this->faker->sentence(10),
            'body_en' => $this->faker->paragraph(3),
            'cover_image_path' => null,
            'is_published' => true,
            'updated_by' => null,
        ];
    }
}
