<?php

namespace Database\Seeders;

use App\Models\Department;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DepartmentsSeeder extends Seeder
{
    public function run(): void
    {
        $departments = [
            'Leadership',
            'Administration',
            'Public Relations',
            'Service Delivery',
            'Planning & Monitoring',
        ];

        foreach ($departments as $index => $name) {
            Department::updateOrCreate(
                ['slug' => Str::slug($name)],
                [
                    'name_en' => $name,
                    'name_am' => '?' . $name . ' ???',
                    'sort_order' => $index + 1,
                    'is_active' => true,
                ]
            );
        }
    }
}
