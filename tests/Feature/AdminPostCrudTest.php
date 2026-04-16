<?php

use App\Models\Post;
use App\Models\User;
use Carbon\Carbon;
use Database\Seeders\RolesAndPermissionsSeeder;
it('allows admin to create update and delete a post', function () {
    $this->seed(RolesAndPermissionsSeeder::class);

    $admin = User::factory()->create();
    $admin->assignRole('Admin');

    $initialPublishDate = Carbon::create(2026, 4, 10, 8, 30, 0, 'UTC');
    $updatedPublishDate = Carbon::create(2026, 4, 20, 14, 45, 0, 'UTC');

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
        'published_at' => $initialPublishDate->toDateTimeString(),
    ];

    $response = $this->actingAs($admin)
        ->post(route('admin.posts.store'), $payload);

    $response->assertRedirect();

    $post = Post::where('title_en', 'Admin Post EN')->firstOrFail();

    expect($post->published_at?->toDateTimeString())->toBe($initialPublishDate->toDateTimeString());

    $updatePayload = $payload;
    $updatePayload['title_en'] = 'Updated Post EN';
    $updatePayload['title_am'] = 'Updated Post AM';
    $updatePayload['published_at'] = $updatedPublishDate->toDateTimeString();

    $updateResponse = $this->actingAs($admin)
        ->put(route('admin.posts.update', $post), $updatePayload);

    $updateResponse->assertRedirect();

    $post->refresh();

    $this->assertDatabaseHas('posts', [
        'id' => $post->id,
        'title_en' => 'Updated Post EN',
    ]);

    expect($post->published_at?->toDateTimeString())->toBe($updatedPublishDate->toDateTimeString());

    $deleteResponse = $this->actingAs($admin)
        ->delete(route('admin.posts.destroy', $post));

    $deleteResponse->assertRedirect();

    $this->assertDatabaseMissing('posts', [
        'id' => $post->id,
    ]);
});
