<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\Document;
use App\Models\Page;
use App\Models\Post;
use App\Models\Staff;
use App\Models\Tender;
use Illuminate\Http\Response;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Route;

class SitemapController extends Controller
{
    private const CACHE_KEY = 'public.sitemap.xml';
    private const CACHE_INDEX_KEY = 'public.sitemap.index';
    private const CACHE_TTL = 1440;

    public function index(): Response
    {
        $content = Cache::remember(self::CACHE_INDEX_KEY, self::CACHE_TTL, fn () => $this->renderIndex());

        return response($content, 200)
            ->header('Content-Type', 'application/xml; charset=utf-8');
    }

    public function sitemap(): Response
    {
        $content = Cache::remember(self::CACHE_KEY, self::CACHE_TTL, function () {
            $entries = $this->gatherEntries();
            return view('sitemap.xml', ['entries' => $entries])->render();
        });

        return response($content, 200)
            ->header('Content-Type', 'application/xml; charset=utf-8');
    }

    public function robots(): Response
    {
        $lines = [
            'User-agent: *',
            'Allow: /',
            'Disallow: /admin',
            'Disallow: /login',
            'Disallow: /logout',
            'Disallow: /service-requests',
            'Disallow: /appointments',
            'Disallow: /locale',
            'Disallow: /newsletter/subscribe',
            'Sitemap: ' . url('/sitemap-index.xml'),
        ];

        return response(implode("\n", $lines) . "\n", 200)
            ->header('Content-Type', 'text/plain; charset=utf-8');
    }

    private function renderIndex(): string
    {
        $sitemapUrl = url('/sitemap.xml');
        $lastMod = now()->toAtomString();

        return <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    <sitemap>
        <loc>{$this->escape($sitemapUrl)}</loc>
        <lastmod>{$lastMod}</lastmod>
    </sitemap>
</sitemapindex>
XML;
    }

    private function renderUrlSet(array $entries): string
    {
        $buffer = [
            '<?xml version="1.0" encoding="UTF-8"?>',
            '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">',
        ];

        foreach ($entries as $entry) {
            $buffer[] = '    <url>';
            $buffer[] = '        <loc>' . $this->escape($entry['loc']) . '</loc>';
            if (! empty($entry['lastmod'])) {
                $buffer[] = '        <lastmod>' . $entry['lastmod'] . '</lastmod>';
            }
            if (! empty($entry['priority'])) {
                $buffer[] = '        <priority>' . $entry['priority'] . '</priority>';
            }
            $buffer[] = '    </url>';
        }

        $buffer[] = '</urlset>';

        return implode("\n", $buffer);
    }

    private function gatherEntries(): array
    {
        $urls = [];

        if (Route::has('home')) {
            $urls[] = $this->buildUrl(route('home'), null, 'daily', '1.0');
        }

        $aboutRoutes = [
            'about' => 'pages.about',
            'mission_vision_values' => 'pages.mission',
            'leadership' => 'pages.leadership',
            'structure' => 'pages.structure',
            'history' => 'pages.history',
        ];

        $pages = Page::whereIn('key', array_keys($aboutRoutes))->get();
        foreach ($pages as $page) {
            $routeName = $aboutRoutes[$page->key] ?? null;
            if ($routeName && Route::has($routeName)) {
                $urls[] = $this->buildUrl(route($routeName), $page->updated_at, 'monthly', '0.6');
            }
        }

        if (Route::has('news.index')) {
            $query = $this->publishedPostsQuery('news');
            $urls[] = $this->buildUrl(route('news.index'), $this->maxUpdatedAt($query), 'daily', '0.9');
            $this->addPostEntries($urls, 'news', 'weekly', '0.7');
        }

        if (Route::has('announcements.index')) {
            $query = $this->publishedPostsQuery('announcement');
            $urls[] = $this->buildUrl(route('announcements.index'), $this->maxUpdatedAt($query), 'daily', '0.8');
            $this->addPostEntries($urls, 'announcement', 'weekly', '0.6');
        }

        if (Route::has('tenders.index')) {
            $urls[] = $this->buildUrl(route('tenders.index'), $this->maxUpdatedAt(Tender::published()), 'daily', '0.9');
            foreach (Tender::published()->get() as $tender) {
                $urls[] = $this->buildUrl(route('tenders.show', $tender), $tender->updated_at, 'weekly', '0.7');
            }
        }

        if (Route::has('citizen-charter.index')) {
            $urls[] = $this->buildUrl(route('citizen-charter.index'), $this->maxUpdatedAt(Department::where('is_active', true)), 'weekly', '0.6');
            $this->addCitizenCharterEntries($urls);
        }

        if (Route::has('downloads.index')) {
            $urls[] = $this->buildUrl(route('downloads.index'), $this->maxUpdatedAt(Document::where('is_published', true)), 'weekly', '0.5');
        }

        if (Route::has('staff.index')) {
            $urls[] = $this->buildUrl(route('staff.index'), $this->maxUpdatedAt(Staff::where('is_active', true)), 'monthly', '0.6');
            foreach (Staff::where('is_active', true)->get() as $member) {
                $urls[] = $this->buildUrl(route('staff.show', $member), $member->updated_at, 'weekly', '0.6');
            }
        }

        if (Route::has('organization.contact')) {
            $urls[] = $this->buildUrl(route('organization.contact'), null, 'monthly', '0.5');
        }

        if (Route::has('vacancies.index')) {
            $urls[] = $this->buildUrl(route('vacancies.index'), null, 'weekly', '0.6');
            if (class_exists(\App\Models\Vacancy::class) && Route::has('vacancies.show')) {
                foreach (\App\Models\Vacancy::all() as $vacancy) {
                    $urls[] = $this->buildUrl(
                        route('vacancies.show', $vacancy),
                        $vacancy->updated_at ?? $vacancy->created_at,
                        'weekly',
                        '0.5'
                    );
                }
            }
        }

        return $urls;
    }

    private function addPostEntries(array &$urls, string $type, string $changefreq, string $priority): void
    {
        $routeName = $type === 'news' ? 'news.show' : 'announcements.show';

        if (! Route::has($routeName)) {
            return;
        }

        $posts = $this->publishedPostsQuery($type)
            ->orderByDesc('published_at')
            ->get();

        foreach ($posts as $post) {
            $urls[] = $this->buildUrl(
                route($routeName, $post->slug),
                $post->updated_at ?? $post->published_at,
                $changefreq,
                $priority
            );
        }
    }

    private function addCitizenCharterEntries(array &$urls): void
    {
        $departments = Department::where('is_active', true)
            ->with(['charterServices' => fn ($query) => $query->active()->orderBy('sort_order')])
            ->orderBy('sort_order')
            ->get();

        foreach ($departments as $department) {
            if (Route::has('citizen-charter.department')) {
                $urls[] = $this->buildUrl(route('citizen-charter.department', $department), $department->updated_at, 'weekly', '0.5');
            }

            foreach ($department->charterServices as $service) {
                if (! Route::has('citizen-charter.service')) {
                    continue;
                }

                $urls[] = $this->buildUrl(
                    route('citizen-charter.service', [$department, $service]),
                    $service->updated_at,
                    'weekly',
                    '0.4'
                );
            }
        }
    }

    private function buildUrl(string $loc, ?Carbon $lastmod = null, ?string $changefreq = null, ?string $priority = null): array
    {
        return [
            'loc' => $loc,
            'lastmod' => $this->formatLastmod($lastmod),
            'changefreq' => $changefreq,
            'priority' => $priority,
        ];
    }

    private function formatLastmod(?Carbon $value): ?string
    {
        return $value ? $value->toAtomString() : null;
    }

    private function publishedPostsQuery(string $type)
    {
        return Post::where('type', $type)
            ->where('is_published', true)
            ->where(function ($query) {
                $query->whereNull('published_at')
                    ->orWhere('published_at', '<=', now());
            });
    }

    private function maxUpdatedAt($query): ?Carbon
    {
        $max = $query->max('updated_at');
        return $max ? Carbon::parse($max) : null;
    }

    private function escape(string $value): string
    {
        return htmlspecialchars($value, ENT_XML1, 'UTF-8');
    }
}
