<?php

namespace Database\Seeders;

use App\Models\Department;
use App\Models\Staff;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class StaffDirectorySeeder extends Seeder
{
    /**
     * Seed sample departments and staff.
     */
    public function run(): void
    {
        $departments = [
            [
                'name_am' => 'Leadership (AM)',
                'name_en' => 'Leadership',
                'slug' => 'leadership',
                'sort_order' => 1,
                'is_active' => true,
            ],
            [
                'name_am' => 'Administration (AM)',
                'name_en' => 'Administration',
                'slug' => 'administration',
                'sort_order' => 2,
                'is_active' => true,
            ],
        ];

        foreach ($departments as $data) {
            Department::firstOrCreate(
                ['slug' => $data['slug']],
                $data
            );
        }

        $leadership = Department::where('slug', 'leadership')->first();
        $admin = Department::where('slug', 'administration')->first();

        if ($leadership) {
            Staff::firstOrCreate(
                ['email' => 'leader@example.com'],
                [
                    'department_id' => $leadership->id,
                    'full_name_am' => 'Leader Name (AM)',
                    'full_name_en' => 'Leader Name',
                    'title_am' => 'Director (AM)',
                    'title_en' => 'Director',
                    'bio_am' => 'Leadership bio (AM).',
                    'bio_en' => 'Leadership bio in English.',
                    'phone' => '+251900000000',
                    'is_active' => true,
                    'is_featured' => true,
                    'sort_order' => 1,
                ]
            );
        }

        if ($admin) {
            Staff::firstOrCreate(
                ['email' => 'adminstaff@example.com'],
                [
                    'department_id' => $admin->id,
                    'full_name_am' => 'Admin Staff (AM)',
                    'full_name_en' => 'Admin Staff',
                    'title_am' => 'Officer (AM)',
                    'title_en' => 'Officer',
                    'bio_am' => 'Administration bio (AM).',
                    'bio_en' => 'Administration bio in English.',
                    'phone' => '+251900000001',
                    'is_active' => true,
                    'is_featured' => false,
                    'sort_order' => 2,
                ]
            );
        }
    }
}
