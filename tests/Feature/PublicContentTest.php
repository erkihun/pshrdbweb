<?php

use App\Models\Document;
use App\Models\Post;
use App\Models\Service;

it('shows only published news posts', function () {
    $published = Post::factory()->create([
        'type' => 'news',
        'title_en' => 'Published News',
        'title_am' => 'Published News (AM)',
        'is_published' => true,
        'published_at' => now()->subDay(),
    ]);

    $draft = Post::factory()->create([
        'type' => 'news',
        'title_en' => 'Draft News',
        'title_am' => 'Draft News (AM)',
        'is_published' => false,
        'published_at' => null,
    ]);

    $future = Post::factory()->create([
        'type' => 'news',
        'title_en' => 'Future News',
        'title_am' => 'Future News (AM)',
        'is_published' => true,
        'published_at' => now()->addDay(),
    ]);

    $response = $this->get('/news');

    $response
        ->assertOk()
        ->assertSee($published->title_en)
        ->assertDontSee($draft->title_en)
        ->assertDontSee($future->title_en);
});

it('shows only active services', function () {
    $active = Service::factory()->create([
        'title_en' => 'Active Service',
        'title_am' => 'Active Service (AM)',
        'is_active' => true,
    ]);

    $inactive = Service::factory()->inactive()->create([
        'title_en' => 'Inactive Service',
        'title_am' => 'Inactive Service (AM)',
    ]);

    $response = $this->get('/services');

    $response
        ->assertOk()
        ->assertSee($active->title_en)
        ->assertDontSee($inactive->title_en);
});

it('shows only published downloads', function () {
    $published = Document::factory()->create([
        'title_en' => 'Published Document',
        'title_am' => 'Published Document (AM)',
        'is_published' => true,
        'published_at' => now()->subDay(),
    ]);

    $draft = Document::factory()->draft()->create([
        'title_en' => 'Draft Document',
        'title_am' => 'Draft Document (AM)',
    ]);

    $future = Document::factory()->create([
        'title_en' => 'Future Document',
        'title_am' => 'Future Document (AM)',
        'is_published' => true,
        'published_at' => now()->addDay(),
    ]);

    $response = $this->get('/downloads');

    $response
        ->assertOk()
        ->assertSee($published->title_en)
        ->assertDontSee($draft->title_en)
        ->assertDontSee($future->title_en);
});
