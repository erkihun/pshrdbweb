<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\Document;
use App\Models\Page;
use App\Models\Post;
use App\Models\Tender;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

class SitemapPageController extends Controller
{
    private const CACHE_KEY = 'public.sitemap.page';
    private const CACHE_TTL = 1440;

    public function index()
    {
        $sections = Cache::remember(self::CACHE_KEY, self::CACHE_TTL, fn () => $this->buildSections());

        $seoMeta = [
            'title' => __('public.sitemap_page.title'),
            'description' => __('public.sitemap_page.description'),
            'canonical' => route('sitemap.page'),
            'url' => route('sitemap.page'),
            'robots' => 'index, follow',
        ];

        return view('public.sitemap', compact('sections', 'seoMeta'));
    }

    private function buildSections(): array
    {
        $sections = [];

        $generalLinks = [];
        if (Route::has('home')) {
            $generalLinks[] = [
                'label' => $this->translateOrFallback('public.navigation.home', 'Home'),
                'url' => route('home'),
            ];
        }
        if (Route::has('organization.contact')) {
            $generalLinks[] = [
                'label' => $this->translateOrFallback('public.navigation.contact', 'Contact'),
                'url' => route('organization.contact'),
            ];
        }
        $this->addSection($sections, 'general', $generalLinks);

        $aboutLinks = $this->collectAboutLinks();
        $this->addSection($sections, 'about', $aboutLinks);

        $staffLinks = [];
        if (Route::has('staff.index')) {
            $staffLinks[] = [
                'label' => __('public.navigation.staff') ?: __('Staff Directory'),
                'url' => route('staff.index'),
            ];
        }
        $this->addSection($sections, 'staff', $staffLinks);

        $citizenGroups = $this->collectCitizenCharterGroups();
        $this->addSection($sections, 'citizen_charter', [], null, $citizenGroups);

        $newsLinks = $this->collectNewsLinks();
        $this->addSection($sections, 'news', $newsLinks);

        $announcementLinks = $this->collectAnnouncementLinks();
        $this->addSection($sections, 'announcements', $announcementLinks);

        $downloadLinks = $this->collectDownloadLinks();
        $this->addSection($sections, 'downloads', $downloadLinks);

        $tenderLinks = $this->collectTenderLinks();
        $this->addSection($sections, 'tenders', $tenderLinks);

        $vacancyLinks = $this->collectVacancyLinks();
        $this->addSection($sections, 'vacancies', $vacancyLinks);

        return $sections;
    }

    private function addSection(array &$sections, string $key, array $links = [], ?string $description = null, array $groups = []): void
    {
        if (empty($links) && empty($groups)) {
            return;
        }

        $sections[] = array_filter([
            'title' => $this->sectionTitle($key),
            'description' => $description,
            'links' => $links,
            'groups' => $groups,
        ]);
    }

    private function sectionTitle(string $key): string
    {
        $translated = __('public.sitemap_page.sections.' . $key);
        if (Str::startsWith($translated, 'public.sitemap_page.sections.')) {
            return Str::of(str_replace('_', ' ', $key))->title();
        }

        return $translated;
    }

    private function collectAboutLinks(): array
    {
        $aboutRoutes = [
            'about' => 'pages.about',
            'mission_vision_values' => 'pages.mission',
            'leadership' => 'pages.leadership',
            'structure' => 'pages.structure',
            'history' => 'pages.history',
        ];

        $pages = Page::whereIn('key', array_keys($aboutRoutes))->get();
        $links = [];
        foreach ($pages as $page) {
            $route = $aboutRoutes[$page->key] ?? null;
            if (! $route || ! Route::has($route)) {
                continue;
            }

            $links[] = [
                'label' => $this->localizedAttribute($page, 'title', __('About')),
                'url' => route($route),
            ];
        }

        return $links;
    }

    private function collectCitizenCharterGroups(): array
    {
        if (! Route::has('citizen-charter.department') && ! Route::has('citizen-charter.service')) {
            return [];
        }

        $departments = Department::where('is_active', true)
            ->with(['charterServices' => fn ($query) => $query->active()->orderBy('sort_order')])
            ->orderBy('sort_order')
            ->get();

        $groups = [];
        foreach ($departments as $department) {
            $groupLinks = [];
            if (Route::has('citizen-charter.department')) {
                $groupLinks[] = [
                    'label' => __('public.sitemap_page.department_overview', ['department' => $department->display_name]),
                    'url' => route('citizen-charter.department', $department),
                ];
            }

            foreach ($department->charterServices as $service) {
                if (! Route::has('citizen-charter.service')) {
                    continue;
                }

                $groupLinks[] = [
                    'label' => $service->display_name ?? $this->localizedAttribute($service, 'name', 'Service'),
                    'url' => route('citizen-charter.service', [$department, $service]),
                ];
            }

            if (empty($groupLinks)) {
                continue;
            }

            $groups[] = [
                'title' => $department->display_name,
                'links' => $groupLinks,
            ];
        }

        return $groups;
    }

    private function collectNewsLinks(): array
    {
        $links = [];
        if (Route::has('news.index')) {
            $links[] = [
                'label' => $this->translateOrFallback('public.navigation.news', 'News'),
                'url' => route('news.index'),
            ];

            $posts = $this->publishedPostsQuery('news')
                ->orderByDesc('published_at')
                ->limit(6)
                ->get();

            foreach ($posts as $post) {
                $links[] = [
                    'label' => $post->display_title ?? $this->localizedAttribute($post, 'title', 'News article'),
                    'url' => Route::has('news.show') ? route('news.show', $post->slug) : '#',
                ];
            }
        }

        return $links;
    }

    private function collectAnnouncementLinks(): array
    {
        $links = [];
        if (Route::has('announcements.index')) {
            $links[] = [
                'label' => $this->translateOrFallback('public.navigation.announcements', 'Announcements'),
                'url' => route('announcements.index'),
            ];

            $posts = $this->publishedPostsQuery('announcement')
                ->orderByDesc('published_at')
                ->limit(6)
                ->get();

            foreach ($posts as $post) {
                $links[] = [
                    'label' => $post->display_title ?? $this->localizedAttribute($post, 'title', 'Announcement'),
                    'url' => Route::has('announcements.show') ? route('announcements.show', $post->slug) : '#',
                ];
            }
        }

        return $links;
    }

    private function collectDownloadLinks(): array
    {
        $links = [];
        if (Route::has('downloads.index')) {
            $links[] = [
                'label' => $this->translateOrFallback('public.navigation.downloads', 'Downloads'),
                'url' => route('downloads.index'),
            ];

            $documents = Document::where('is_published', true)
                ->orderByDesc('published_at')
                ->limit(6)
                ->get();

            foreach ($documents as $document) {
                $documentUrl = Route::has('downloads.show') ? route('downloads.show', $document) : '#';
                $links[] = [
                    'label' => $document->display_title ?? $this->localizedAttribute($document, 'title', 'Document'),
                    'url' => $documentUrl,
                ];
            }
        }

        return $links;
    }

    private function collectTenderLinks(): array
    {
        $links = [];
        if (Route::has('tenders.index')) {
            $links[] = [
                'label' => $this->translateOrFallback('public.navigation.tenders', 'Tenders'),
                'url' => route('tenders.index'),
            ];

            $tenders = Tender::published()->orderByDesc('published_at')->limit(6)->get();
            foreach ($tenders as $tender) {
                $tenderUrl = Route::has('tenders.show') ? route('tenders.show', $tender) : '#';
                $links[] = [
                    'label' => $tender->title ?? $this->localizedAttribute($tender, 'title', 'Tender'),
                    'url' => $tenderUrl,
                ];
            }
        }

        return $links;
    }

    private function collectVacancyLinks(): array
    {
        $links = [];
        if (! Route::has('vacancies.index')) {
            return $links;
        }

        $links[] = [
            'label' => __('public.sitemap_page.sections.vacancies') ?: __('public.navigation.vacancies'),
            'url' => route('vacancies.index'),
        ];

        if (! class_exists(\App\Models\Vacancy::class) || ! Route::has('vacancies.show')) {
            return $links;
        }

        $vacancies = \App\Models\Vacancy::orderByDesc('created_at')->limit(6)->get();
        foreach ($vacancies as $vacancy) {
            $links[] = [
                'label' => $this->localizedAttribute($vacancy, 'title', __('Vacancy')),
                'url' => route('vacancies.show', $vacancy),
            ];
        }

        return $links;
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

    private function translateOrFallback(string $key, string $fallback): string
    {
        $translation = __($key);
        return $translation === $key ? $fallback : $translation;
    }

    private function localizedAttribute($model, string $base, string $fallback = ''): string
    {
        $locale = app()->getLocale();
        $localizedField = "{$base}_{$locale}";
        if (! empty($model->{$localizedField})) {
            return $model->{$localizedField};
        }

        $englishField = "{$base}_en";
        if (! empty($model->{$englishField})) {
            return $model->{$englishField};
        }

        if (! empty($model->{$base})) {
            return $model->{$base};
        }

        return $fallback;
    }
}
