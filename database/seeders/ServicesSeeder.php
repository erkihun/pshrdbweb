<?php

namespace Database\Seeders;

use App\Models\Service;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ServicesSeeder extends Seeder
{
    public function run(): void
    {
        for ($i = 1; $i <= 12; $i++) {
            Service::factory()->create([
                'title_en' => 'Public Service ' . $i,
                'title_am' => '?????? ' . $i,
                'description_en' => 'Service description for item ' . $i . '. It covers requirements and steps.',
                'description_am' => '??????? ' . $i . ' ????? ??????? ?? ????? ??????',
                'requirements_en' => "1) Application form\n2) ID copy\n3) Payment receipt",
                'requirements_am' => "1) ????? ??\n2) ????? ??\n3) ???? ????",
                'sort_order' => $i,
                'is_active' => true,
                'slug' => Str::slug('public-service-' . $i),
            ]);
        }
    }
}
