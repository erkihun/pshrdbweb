<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class SubscriberController extends Controller
{
    public function index()
    {
        if (! Schema::hasTable('subscribers')) {
            return view('admin.subscribers.index', ['hasTable' => false]);
        }

        $metrics = DB::table('subscribers')
            ->selectRaw('COUNT(*) as total, SUM(CASE WHEN is_active = 1 THEN 1 ELSE 0 END) as active')
            ->selectRaw('SUM(CASE WHEN verified_at IS NOT NULL THEN 1 ELSE 0 END) as verified')
            ->first();

        $total = $metrics->total ?? 0;

        $localeDistribution = DB::table('subscribers')
            ->select('locale', DB::raw('COUNT(*) as count'))
            ->groupBy('locale')
            ->orderByDesc('count')
            ->pluck('count', 'locale');

        $recent = DB::table('subscribers')
            ->orderByDesc('created_at')
            ->limit(10)
            ->get(['email', 'phone', 'locale', 'is_active', 'verified_at', 'created_at']);

        return view('admin.subscribers.index', [
            'hasTable' => true,
            'total' => $total,
            'active' => $metrics->active ?? 0,
            'verified' => $metrics->verified ?? 0,
            'localeDistribution' => $localeDistribution,
            'recent' => $recent,
        ]);
    }
}
