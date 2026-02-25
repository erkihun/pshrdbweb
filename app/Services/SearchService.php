<?php

namespace App\Services;

use App\Models\Document;
use App\Models\Page;
use App\Models\Post;
use App\Models\Service;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class SearchService
{
    public function search(string $query, string $locale, array $pagination = []): array
    {
        $trimmed = trim($query);
        $hasQuery = $trimmed !== '';

        $results = [
            'query' => $trimmed,
            'hasQuery' => $hasQuery,
            'posts' => null,
            'services' => null,
            'documents' => null,
            'pages' => null,
        ];

        if (! $hasQuery) {
            return $results;
        }

        $results['posts'] = $this->cachePaginator('posts', $trimmed, $locale, $pagination['posts'] ?? null, function () use ($trimmed, $locale) {
            return $this->postsQuery($trimmed, $locale)
                ->paginate(10, ['*'], 'posts_page')
                ->withQueryString();
        });

        $results['services'] = $this->cachePaginator('services', $trimmed, $locale, $pagination['services'] ?? null, function () use ($trimmed, $locale) {
            return $this->servicesQuery($trimmed, $locale)
                ->paginate(10, ['*'], 'services_page')
                ->withQueryString();
        });

        $results['documents'] = $this->cachePaginator('documents', $trimmed, $locale, $pagination['documents'] ?? null, function () use ($trimmed, $locale) {
            return $this->documentsQuery($trimmed, $locale)
                ->paginate(10, ['*'], 'documents_page')
                ->withQueryString();
        });

        $results['pages'] = $this->cachePaginator('pages', $trimmed, $locale, $pagination['pages'] ?? null, function () use ($trimmed, $locale) {
            return $this->pagesQuery($trimmed, $locale)
                ->paginate(10, ['*'], 'pages_page')
                ->withQueryString();
        });

        return $results;
    }

    private function cachePaginator(string $group, string $query, string $locale, ?string $page, callable $callback)
    {
        $key = sprintf('search:%s:%s:%s:%s', $group, $locale, md5($query), $page ?: '1');

        return Cache::remember($key, 300, $callback);
    }

    private function postsQuery(string $query, string $locale): Builder
    {
        $title = $locale === 'am' ? 'title_am' : 'title_en';
        $excerpt = $locale === 'am' ? 'excerpt_am' : 'excerpt_en';
        $body = $locale === 'am' ? 'body_am' : 'body_en';

        $builder = Post::query()
            ->where('is_published', true)
            ->where(function ($query) {
                $query->whereNull('published_at')
                    ->orWhere('published_at', '<=', now());
            });

        $this->applySearch($builder, [$title, $excerpt, $body], $query, $title, $excerpt, $body);

        return $builder;
    }

    private function servicesQuery(string $query, string $locale): Builder
    {
        $title = $locale === 'am' ? 'title_am' : 'title_en';
        $description = $locale === 'am' ? 'description_am' : 'description_en';
        $requirements = $locale === 'am' ? 'requirements_am' : 'requirements_en';

        $builder = Service::query()->where('is_active', true);

        $this->applySearch($builder, [$title, $description, $requirements], $query, $title, $description, $requirements);

        return $builder;
    }

    private function documentsQuery(string $query, string $locale): Builder
    {
        $title = $locale === 'am' ? 'title_am' : 'title_en';
        $description = $locale === 'am' ? 'description_am' : 'description_en';

        $builder = Document::query()
            ->where('is_published', true)
            ->where(function ($query) {
                $query->whereNull('published_at')
                    ->orWhere('published_at', '<=', now());
            });

        $this->applySearch($builder, [$title, $description], $query, $title, $description);

        return $builder;
    }

    private function pagesQuery(string $query, string $locale): Builder
    {
        $title = $locale === 'am' ? 'title_am' : 'title_en';
        $body = $locale === 'am' ? 'body_am' : 'body_en';

        $builder = Page::query()->where('is_published', true);

        $this->applySearch($builder, [$title, $body], $query, $title, $body);

        return $builder;
    }

    private function applySearch(Builder $builder, array $columns, string $query, string $primary, ?string $secondary = null, ?string $tertiary = null): void
    {
        if (DB::getDriverName() === 'mysql') {
            $booleanQuery = $this->toBooleanQuery($query);
            $columnList = implode(', ', $columns);

            $builder->select('*')
                ->selectRaw("MATCH($columnList) AGAINST (? IN BOOLEAN MODE) as relevance", [$booleanQuery])
                ->whereRaw("MATCH($columnList) AGAINST (? IN BOOLEAN MODE)", [$booleanQuery])
                ->orderByDesc('relevance');

            return;
        }

        $like = '%' . $query . '%';
        $builder->where(function ($search) use ($columns, $like) {
            foreach ($columns as $column) {
                $search->orWhere($column, 'like', $like);
            }
        });

        $ranking = [];
        $bindings = [];

        if ($primary) {
            $ranking[] = "CASE WHEN $primary LIKE ? THEN 3 ELSE 0 END";
            $bindings[] = $like;
        }
        if ($secondary) {
            $ranking[] = "CASE WHEN $secondary LIKE ? THEN 2 ELSE 0 END";
            $bindings[] = $like;
        }
        if ($tertiary) {
            $ranking[] = "CASE WHEN $tertiary LIKE ? THEN 1 ELSE 0 END";
            $bindings[] = $like;
        }

        if ($ranking) {
            $builder->orderByRaw('(' . implode(' + ', $ranking) . ') DESC', $bindings);
        }
    }

    private function toBooleanQuery(string $query): string
    {
        $terms = preg_split('/\s+/', Str::lower($query), -1, PREG_SPLIT_NO_EMPTY);

        $terms = array_map(function (string $term) {
            $term = preg_replace('/[^a-z0-9\-\_]/', '', $term);

            if (Str::length($term) < 2) {
                return null;
            }

            return $term . '*';
        }, $terms);

        $terms = array_filter($terms);

        return $terms ? implode(' ', $terms) : $query;
    }
}
