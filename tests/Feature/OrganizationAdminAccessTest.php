<?php

use App\Models\Organization;
use App\Models\User;
use Database\Seeders\RolesAndPermissionsSeeder;

it('allows organization admin to update their own organization', function () {
    $this->seed(RolesAndPermissionsSeeder::class);

    $organization = Organization::create([
        'name' => 'Finance Bureau',
        'code' => 'FIN',
        'is_active' => true,
    ]);

    $user = User::factory()->create([
        'organization_id' => $organization->id,
    ]);
    $user->assignRole('Organization Admin');

    $response = $this->actingAs($user)->put(route('admin.organizations.update', $organization), [
        'name' => 'Finance Bureau Updated',
        'code' => 'FIN',
        'is_active' => true,
    ]);

    $response->assertRedirect(route('admin.organizations.index'));
    $this->assertDatabaseHas('organizations', [
        'id' => $organization->id,
        'name' => 'Finance Bureau Updated',
    ]);
});

it('prevents organization admin from accessing other organizations', function () {
    $this->seed(RolesAndPermissionsSeeder::class);

    $ownOrganization = Organization::create([
        'name' => 'Public Works',
        'code' => 'PW',
        'is_active' => true,
    ]);

    $otherOrganization = Organization::create([
        'name' => 'Health Office',
        'code' => 'HO',
        'is_active' => true,
    ]);

    $user = User::factory()->create([
        'organization_id' => $ownOrganization->id,
    ]);
    $user->assignRole('Organization Admin');

    $showResponse = $this->actingAs($user)->get(route('admin.organizations.show', $otherOrganization));
    $showResponse->assertForbidden();

    $updateResponse = $this->actingAs($user)->put(route('admin.organizations.update', $otherOrganization), [
        'name' => 'Health Office Updated',
        'code' => 'HO',
        'is_active' => true,
    ]);
    $updateResponse->assertForbidden();
});
