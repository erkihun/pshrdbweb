<?php

use App\Models\Document;
use App\Models\Post;
use App\Models\Service;
use App\Models\Vacancy;

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

it('shows only published vacancies on the public vacancy index', function () {
    $published = Vacancy::create([
        'title' => 'Published Vacancy',
        'description' => 'Published vacancy description',
        'location' => 'Addis Ababa',
        'status' => Vacancy::STATUS_OPEN,
        'deadline' => today()->addDays(10),
        'published_at' => now()->subDay(),
        'is_published' => true,
        'code' => 'PUB-001',
        'notes' => 'Published vacancy note',
        'slots' => 3,
    ]);

    Vacancy::create([
        'title' => 'Draft Vacancy',
        'description' => 'Draft vacancy description',
        'location' => 'Addis Ababa',
        'status' => Vacancy::STATUS_OPEN,
        'deadline' => today()->addDays(10),
        'published_at' => null,
        'is_published' => false,
        'code' => 'DRF-001',
        'notes' => 'Draft vacancy note',
        'slots' => 2,
    ]);

    Vacancy::create([
        'title' => 'Scheduled Vacancy',
        'description' => 'Scheduled vacancy description',
        'location' => 'Addis Ababa',
        'status' => Vacancy::STATUS_OPEN,
        'deadline' => today()->addDays(10),
        'published_at' => now()->addDay(),
        'is_published' => true,
        'code' => 'SCH-001',
        'notes' => 'Scheduled vacancy note',
        'slots' => 4,
    ]);

    $response = $this->get('/vacancies');

    $response
        ->assertOk()
        ->assertSee($published->title)
        ->assertDontSee('Draft Vacancy')
        ->assertDontSee('Scheduled Vacancy');
});
