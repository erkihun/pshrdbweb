<?php

use App\Models\Post;
use App\Models\User;
use Database\Seeders\RolesAndPermissionsSeeder;
it('allows admin to create update and delete a post', function () {
    $this->seed(RolesAndPermissionsSeeder::class);

    $admin = User::factory()->create();
    $admin->assignRole('Admin');

    $payload = [
        'type' => 'news',
        'title_am' => 'Admin Post AM',
        'title_en' => 'Admin Post EN',
        'excerpt_am' => 'Excerpt AM',
        'excerpt_en' => 'Excerpt EN',
        'body_am' => 'Body AM',
        'body_en' => 'Body EN',
        'seo_title' => 'SEO Title',
        'seo_description' => 'SEO Desc',
        'is_published' => true,
        'published_at' => now()->toDateTimeString(),
    ];

    $response = $this->actingAs($admin)
        ->post(route('admin.posts.store'), $payload);

    $response->assertRedirect();

    $post = Post::where('title_en', 'Admin Post EN')->firstOrFail();

    $updatePayload = $payload;
    $updatePayload['title_en'] = 'Updated Post EN';
    $updatePayload['title_am'] = 'Updated Post AM';

    $updateResponse = $this->actingAs($admin)
        ->put(route('admin.posts.update', $post), $updatePayload);

    $updateResponse->assertRedirect();

    $this->assertDatabaseHas('posts', [
        'id' => $post->id,
        'title_en' => 'Updated Post EN',
    ]);

    $deleteResponse = $this->actingAs($admin)
        ->delete(route('admin.posts.destroy', $post));

    $deleteResponse->assertRedirect();

    $this->assertDatabaseMissing('posts', [
        'id' => $post->id,
    ]);
});
