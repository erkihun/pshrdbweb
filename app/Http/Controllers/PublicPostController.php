<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Services\PublicCacheService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class PublicPostController extends Controller
{
    public function newsIndex(Request $request)
    {
        return $this->indexByType($request, 'news');
    }

    public function announcementsIndex(Request $request)
    {
        return $this->indexByType($request, 'announcement');
    }

    public function newsShow(string $slug)
    {
        return $this->showByType($slug, 'news');
    }

    public function announcementsShow(string $slug)
    {
        return $this->showByType($slug, 'announcement');
    }

    private function indexByType(Request $request, string $type)
    {
        $locale = app()->getLocale();
        $page = (int) $request->get('page', 1);
        $search = $request->get('q');

        $query = Post::where('type', $type)
            ->where('is_published', true)
            ->where(function ($q) {
                $q->whereNull('published_at')
                    ->orWhere('published_at', '<=', now());
            })
            ->orderByDesc('published_at')
            ->orderByDesc('created_at');

        if ($request->filled('q')) {
            $query->where(function ($q) use ($search) {
                $q->where('title_am', 'like', '%' . $search . '%')
                    ->orWhere('title_en', 'like', '%' . $search . '%')
                    ->orWhere('excerpt_am', 'like', '%' . $search . '%')
                    ->orWhere('excerpt_en', 'like', '%' . $search . '%');
            });
        }

        if (! $request->filled('q')) {
            $key = PublicCacheService::key($type, $locale, $page);
            $posts = Cache::remember($key, PublicCacheService::TTL, function () use ($query) {
                return $query->paginate(9)->withQueryString();
            });
        } else {
            $posts = $query->paginate(9)->withQueryString();
        }

        return view('public-posts.index', [
            'posts' => $posts,
            'type' => $type,
        ]);
    }

    private function showByType(string $slug, string $type)
    {
        $post = Post::where('type', $type)
            ->where('is_published', true)
            ->where(function ($q) {
                $q->whereNull('published_at')
                    ->orWhere('published_at', '<=', now());
            })
            ->where('slug', $slug)
            ->firstOrFail();

        $relatedPosts = Post::where('type', $type)
            ->where('is_published', true)
            ->where(function ($q) {
                $q->whereNull('published_at')
                    ->orWhere('published_at', '<=', now());
            })
            ->where('id', '<>', $post->id)
            ->orderByDesc('published_at')
            ->orderByDesc('created_at')
            ->take(50)
            ->get();

        $weatherInfo = $this->resolveWeatherInfo();

        return view('public-posts.show', [
            'post' => $post,
            'type' => $type,
            'relatedPosts' => $relatedPosts,
            'weatherInfo' => $weatherInfo,
        ]);
    }

    private function resolveWeatherInfo(): string
    {
        return Cache::remember('weather.addis_ababa', 300, function () {
            try {
                $response = Http::timeout(5)->get('https://wttr.in/Addis%20Ababa?format=%l+%c+%t');
                if ($response->ok()) {
                    return trim($response->body());
                }
            } catch (\Exception $e) {
                //
            }

            return 'Addis Ababa weather unavailable';
        });
    }
}
