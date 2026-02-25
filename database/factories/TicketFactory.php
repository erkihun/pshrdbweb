<?php

namespace Database\Factories;

use App\Models\Ticket;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class TicketFactory extends Factory
{
    protected $model = Ticket::class;

    public function definition(): array
    {
        return [
            'reference_code' => 'TKT-' . Str::upper(Str::random(8)),
            'name' => $this->faker->name,
            'email' => $this->faker->safeEmail,
            'phone' => $this->faker->phoneNumber,
            'subject' => $this->faker->sentence(6),
            'message' => $this->faker->paragraph,
            'status' => 'open',
        ];
    }
}
