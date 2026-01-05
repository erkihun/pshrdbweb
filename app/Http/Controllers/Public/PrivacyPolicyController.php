<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Page;
use Illuminate\Support\Facades\Cache;

class PrivacyPolicyController extends Controller
{
    private const CACHE_KEY = 'public.privacy-policy';
    private const CACHE_TTL = 1440;
    private const KEY = 'privacy_policy';

    public function show()
    {
        $policy = Cache::remember(self::CACHE_KEY, self::CACHE_TTL, function () {
            return Page::where('key', self::KEY)
                ->where('is_published', true)
                ->first();
        });

        $title = $policy?->display_title ?: __('public.privacy_page.title');
        $description = __('public.privacy_page.description');

        $seoMeta = [
            'title' => $title,
            'description' => $description,
            'canonical' => route('privacy'),
            'url' => route('privacy'),
            'robots' => 'index, follow',
        ];

        return view('public.privacy', compact('policy', 'seoMeta', 'title', 'description'));
    }
}
