<?php

namespace Database\Factories;

use App\Models\Staff;
use Illuminate\Database\Eloquent\Factories\Factory;

class StaffFactory extends Factory
{
    protected $model = Staff::class;

    public function definition(): array
    {
        return [
            'department_id' => null,
            'full_name_am' => '?? ?? ' . $this->faker->firstName,
            'full_name_en' => $this->faker->name,
            'title_am' => '??? ' . $this->faker->word,
            'title_en' => $this->faker->jobTitle,
            'bio_am' => '?? ???? ???? ??? ' . $this->faker->sentence(8),
            'bio_en' => $this->faker->paragraph(2),
            'photo_path' => null,
            'phone' => $this->faker->phoneNumber,
            'email' => $this->faker->safeEmail,
            'is_active' => true,
            'is_featured' => false,
            'sort_order' => $this->faker->numberBetween(1, 20),
        ];
    }
}
