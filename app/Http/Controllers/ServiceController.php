<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Services\PublicCacheService;
use App\Models\ServiceFeedback;
use Illuminate\Support\Facades\Cache;

class ServiceController extends Controller
{
    public function index()
    {
        $locale = app()->getLocale();
        $cacheKey = PublicCacheService::key('services', $locale);

        $services = Cache::remember($cacheKey, PublicCacheService::TTL, function () {
            return Service::where('is_active', true)
                ->orderBy('sort_order')
                ->get();
        });

        return view('services.index', compact('services'));
    }

    public function show(string $slug)
    {
        $service = Service::where('is_active', true)
            ->where('slug', $slug)
            ->firstOrFail();

        $feedback = $service->feedback()
            ->where('is_approved', true)
            ->latest('submitted_at')
            ->take(5)
            ->get();

        $average = $service->feedback()
            ->where('is_approved', true)
            ->selectRaw('avg(rating) as avg_rating, count(*) as total')
            ->first();

        $locale = app()->getLocale();
        $cacheKey = PublicCacheService::key('services', $locale);
        $services = Cache::remember($cacheKey, PublicCacheService::TTL, function () {
            return Service::where('is_active', true)
                ->orderBy('sort_order')
                ->get();
        });

        return view('services.show', [
            'service' => $service,
            'feedback' => $feedback,
            'averageRating' => $average?->avg_rating ? round($average->avg_rating, 1) : null,
            'feedbackCount' => $average?->total ?? 0,
            'services' => $services,
        ]);
    }
}
