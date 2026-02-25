<?php

use App\Models\Document;
use App\Models\User;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

it('allows admin to upload a document', function () {
    $this->seed(RolesAndPermissionsSeeder::class);

    $admin = User::factory()->create();
    $admin->assignRole('Admin');

    Storage::fake('public');

    $response = $this->actingAs($admin)
        ->post(route('admin.documents.store'), [
            'title_am' => 'Doc AM',
            'title_en' => 'Doc EN',
            'description_am' => 'Desc AM',
            'description_en' => 'Desc EN',
            'file' => UploadedFile::fake()->create('sample.pdf', 200, 'application/pdf'),
            'is_published' => true,
            'published_at' => now()->toDateTimeString(),
        ]);

    $response->assertRedirect();

    $document = Document::where('title_en', 'Doc EN')->firstOrFail();

    Storage::disk('public')->assertExists($document->file_path);
});
