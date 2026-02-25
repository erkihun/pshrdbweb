<?php

namespace Database\Seeders;

use App\Models\DocumentCategory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DocumentCategoriesSeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            'Public Forms',
            'Policies & Directives',
            'Guidelines',
            'Annual Reports',
            'Citizen Services',
        ];

        foreach ($categories as $index => $name) {
            DocumentCategory::updateOrCreate(
                ['slug' => Str::slug($name)],
                [
                    'name' => $name,
                    'name_am' => $name,
                    'sort_order' => $index + 1,
                    'is_active' => true,
                ]
            );
        }
    }
}
