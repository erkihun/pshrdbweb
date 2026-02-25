<?php

namespace Database\Seeders;

use App\Models\DocumentRequestType;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DocumentRequestTypesSeeder extends Seeder
{
    public function run(): void
    {
        $types = [
            [
                'name_am' => '???? ??? ???',
                'name_en' => 'Certificate Request',
                'slug' => 'certificate',
                'requirements_am' => '???? ????? ? ??????? ????',
                'requirements_en' => 'Valid ID and service description',
            ],
            [
                'name_am' => '????? ? ???? ???',
                'name_en' => 'Official Letter Request',
                'slug' => 'official-letter',
                'requirements_am' => '?????? ???? ? ?????',
                'requirements_en' => 'Letter draft and ID',
            ],
        ];

        foreach ($types as $index => $type) {
            DocumentRequestType::updateOrCreate([
                'slug' => $type['slug'],
            ], [
                'name_am' => $type['name_am'],
                'name_en' => $type['name_en'],
                'requirements_am' => $type['requirements_am'],
                'requirements_en' => $type['requirements_en'],
                'sort_order' => $index,
                'is_active' => true,
            ]);
        }
    }
}
