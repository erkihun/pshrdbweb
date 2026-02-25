<?php

namespace Tests\Feature;

use App\Models\CharterService;
use App\Models\Department;
use App\Models\Document;
use App\Models\DocumentCategory;
use App\Models\Page;
use App\Models\Post;
use App\Models\Staff;
use App\Models\Tender;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class SitemapTest extends TestCase
{
    use RefreshDatabase;

    public function test_sitemap_xml_contains_public_urls()
    {
        Cache::flush();

        Page::factory()->create([
            'key' => 'about',
            'title_en' => 'About',
            'title_am' => 'About AM',
        ]);

        $news = Post::factory()->create([
            'type' => 'news',
            'slug' => 'news-story',
            'is_published' => true,
            'published_at' => now()->subDay(),
        ]);

        $announcement = Post::factory()->announcement()->create([
            'slug' => 'announcement',
            'published_at' => now()->subDays(2),
        ]);

        $department = Department::factory()->create();

        CharterService::create([
            'department_id' => $department->id,
            'name_am' => 'አገልግሎት',
            'name_en' => 'Service One',
            'slug' => 'service-one',
            'sort_order' => 1,
            'is_active' => true,
            'service_delivery_mode' => 'in_person',
            'working_days' => ['Monday'],
        ]);

        Staff::factory()->create([
            'department_id' => $department->id,
            'is_active' => true,
        ]);

        Tender::create([
            'title' => 'Tender One',
            'description' => 'Tender description',
            'status' => 'open',
            'slug' => 'tender-one',
            'published_at' => now()->subDay(),
            'closing_date' => now()->addWeek(),
        ]);

        $category = DocumentCategory::factory()->create();
        Document::factory()->create([
            'document_category_id' => $category->id,
        ]);

        $response = $this->get('/sitemap.xml');

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/xml; charset=utf-8');
        $response->assertSee(route('news.show', $news->slug));
        $response->assertSee(route('announcements.show', $announcement->slug));
        $response->assertSee(route('tenders.show', 'tender-one'));
        $response->assertSee(route('citizen-charter.service', [$department, 'service-one']));
        $response->assertSee(route('staff.index'));
        $response->assertSee(route('organization.contact'));
        $response->assertSee(route('downloads.index'));
    }

    public function test_robots_txt_lists_sitemap()
    {
        $response = $this->get('/robots.txt');

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'text/plain; charset=utf-8');
        $response->assertSee('User-agent: *');
        $response->assertSee('Disallow: /admin');
        $response->assertSee('Sitemap: ' . url('/sitemap-index.xml'));
    }
}
