<?php

namespace Database\Seeders;

use App\Models\Department;
use App\Models\Staff;
use Illuminate\Database\Seeder;

class StaffSeeder extends Seeder
{
    public function run(): void
    {
        $departments = Department::all();
        if ($departments->isEmpty()) {
            return;
        }

        foreach ($departments as $index => $department) {
            $count = $department->slug === 'leadership' ? 3 : 2;

            for ($i = 1; $i <= $count; $i++) {
                Staff::create([
                    'department_id' => $department->id,
                    'full_name_en' => $department->name_en . ' Member ' . $i,
                    'full_name_am' => $department->name_am . ' ??? ' . $i,
                    'title_en' => $department->name_en . ' Officer',
                    'title_am' => $department->name_am . ' ????',
                    'bio_en' => 'Profile for ' . $department->name_en . ' team member ' . $i . '.',
                    'bio_am' => $department->name_am . ' ??? ' . $i . ' ?? ?? ?????',
                    'phone' => '+25190000000' . $i,
                    'email' => 'staff' . $index . $i . '@example.com',
                    'is_active' => true,
                    'is_featured' => $department->slug === 'leadership',
                    'sort_order' => $i,
                ]);
            }
        }
    }
}
