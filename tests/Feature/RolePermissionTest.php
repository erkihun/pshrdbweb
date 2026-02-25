<?php

use App\Models\User;
use Database\Seeders\RolesAndPermissionsSeeder;

it('prevents editor from managing users', function () {
    $this->seed(RolesAndPermissionsSeeder::class);

    $editor = User::factory()->create();
    $editor->assignRole('Editor');

    $response = $this->actingAs($editor)
        ->get(route('admin.users.index'));

    $response->assertForbidden();
});
