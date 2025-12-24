<?php

use App\Models\Ticket;
use App\Models\User;
use Database\Seeders\RolesAndPermissionsSeeder;

it('allows admin to change ticket status and reply', function () {
    $this->seed(RolesAndPermissionsSeeder::class);

    $admin = User::factory()->create();
    $admin->assignRole('Admin');

    $ticket = Ticket::factory()->create([
        'status' => 'open',
    ]);

    $response = $this->actingAs($admin)
        ->put(route('admin.tickets.update', $ticket), [
            'status' => 'resolved',
            'admin_reply' => 'Resolved with details.',
        ]);

    $response->assertRedirect();

    $this->assertDatabaseHas('tickets', [
        'id' => $ticket->id,
        'status' => 'resolved',
        'admin_reply' => 'Resolved with details.',
    ]);
});
