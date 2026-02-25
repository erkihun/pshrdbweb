<?php

namespace Database\Factories;

use App\Models\Document;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class DocumentFactory extends Factory
{
    protected $model = Document::class;

    public function definition(): array
    {
        $title = $this->faker->sentence(6);
        $slug = Str::slug($title) . '-' . Str::lower(Str::random(6));

        return [
            'document_category_id' => null,
            'title' => $title,
            'title_am' => $title . ' (AM)',
            'title_en' => $title,
            'slug' => $slug,
            'description' => $this->faker->paragraph,
            'description_am' => $this->faker->paragraph . ' (AM)',
            'description_en' => $this->faker->paragraph,
            'file_path' => 'documents/' . $slug . '.pdf',
            'file_type' => 'pdf',
            'file_size' => 123456,
            'is_published' => true,
            'published_at' => now()->subDay(),
        ];
    }

    public function draft(): static
    {
        return $this->state([
            'is_published' => false,
            'published_at' => null,
        ]);
    }
}
