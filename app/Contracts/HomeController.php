<?php

namespace App\Contracts;

use App\Http\Controllers\Controller;
use App\Models\HomeSlide;
use App\Models\Post;
use App\Models\OfficialMessage;
use Illuminate\Support\Facades\Cache;

class HomeController extends Controller
{
    public function index()
    {
        // Cache data with appropriate TTL
        $slides = Cache::remember('home_slides', now()->addHours(1), function() {
            return HomeSlide::query()
                ->active()
                ->ordered()
                ->get();
        });

        $officialMessage = Cache::remember('official_message_active', now()->addHours(1), function() {
            return OfficialMessage::query()
                ->where('is_active', true)
                ->first();
        });

        $latestNews = Cache::remember('latest_news_homepage', now()->addMinutes(30), function() {
            return Post::query()
                ->whereNotNull('published_at')
                ->where('status', 'published')
                ->orderByDesc('published_at')
                ->take(6)
                ->get();
        });

        return view('home', compact('slides', 'officialMessage', 'latestNews'));
    }

    // Clear homepage cache when content is updated
    public static function clearHomepageCache()
    {
        Cache::forget('home_slides');
        Cache::forget('official_message_active');
        Cache::forget('latest_news_homepage');
    }
}
