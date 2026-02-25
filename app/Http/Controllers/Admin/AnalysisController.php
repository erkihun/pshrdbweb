<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AnalysisController extends Controller
{
    private const ACTIVE_WINDOW_DAYS = 30;

    public function __construct()
    {
        $this->middleware('permission:view analytics');
    }

    public function index(Request $request)
    {
        $start = $this->resolveDate($request->query('start'), Carbon::now()->subDays(30));
        $end = $this->resolveDate($request->query('end'), Carbon::now());

        if ($start->gt($end)) {
            [$start, $end] = [$end, $start];
        }

        $pageviews = 0;
        $visits = 0;
        $uniqueVisitors = 0;
        $authenticatedUsers = 0;

        $startDay = $start->copy()->startOfDay();
        $endDay = $end->copy()->endOfDay();
        $hasDailyStats = Schema::hasTable('analytics_daily_stats');
        $hasDailyStatsRows = false;

        if ($hasDailyStats) {
            $hasDailyStatsRows = DB::table('analytics_daily_stats')
                ->whereBetween('date', [$start->toDateString(), $end->toDateString()])
                ->exists();
        }

        if ($hasDailyStatsRows) {
            $stats = DB::table('analytics_daily_stats')
                ->whereBetween('date', [$start->toDateString(), $end->toDateString()])
                ->selectRaw('SUM(pageviews) as pageviews, SUM(visits) as visits, SUM(unique_visitors) as unique_visitors, SUM(authenticated_users) as authenticated_users')
                ->first();

            $pageviews = (int) ($stats->pageviews ?? 0);
            $visits = (int) ($stats->visits ?? 0);
            $uniqueVisitors = (int) ($stats->unique_visitors ?? 0);
            $authenticatedUsers = (int) ($stats->authenticated_users ?? 0);
        } else {
            if (Schema::hasTable('analytics_pageviews')) {
                $pageviews = DB::table('analytics_pageviews')
                    ->whereBetween('created_at', [$startDay, $endDay])
                    ->count();

                $authenticatedUsers = DB::table('analytics_pageviews')
                    ->whereBetween('created_at', [$startDay, $endDay])
                    ->whereNotNull('user_id')
                    ->distinct('user_id')
                    ->count('user_id');
            }

            if (Schema::hasTable('analytics_visits')) {
                $visits = DB::table('analytics_visits')
                    ->whereBetween('first_seen_at', [$startDay, $endDay])
                    ->count();

                $uniqueVisitors = DB::table('analytics_visits')
                    ->whereBetween('last_seen_at', [$startDay, $endDay])
                    ->distinct('ip_hash')
                    ->count('ip_hash');
            }
        }

        $topPages = DB::table('analytics_pageviews')
            ->select('path', DB::raw('COUNT(*) as hits'))
            ->whereBetween('created_at', [$start->copy()->startOfDay(), $end->copy()->endOfDay()])
            ->groupBy('path')
            ->orderByDesc('hits')
            ->limit(10)
            ->get();

        $topReferrers = DB::table('analytics_pageviews')
            ->select(DB::raw('COALESCE(referrer, "Direct") as referrer'), DB::raw('COUNT(*) as hits'))
            ->whereBetween('created_at', [$start->copy()->startOfDay(), $end->copy()->endOfDay()])
            ->groupBy('referrer')
            ->orderByDesc('hits')
            ->limit(10)
            ->get();

        $deviceCounts = DB::table('analytics_visits')
            ->select('device_type', DB::raw('COUNT(*) as count'))
            ->whereBetween('last_seen_at', [$start->copy()->startOfDay(), $end->copy()->endOfDay()])
            ->groupBy('device_type')
            ->get()
            ->keyBy('device_type');

        $deviceBreakdown = collect(['desktop', 'mobile', 'tablet', 'bot', 'unknown'])
            ->map(fn (string $type) => [
                'type' => ucfirst($type),
                'count' => (int) ($deviceCounts[$type]->count ?? 0),
            ])
            ->values();

        $browserBreakdown = DB::table('analytics_visits')
            ->select(DB::raw('COALESCE(browser, "Unknown") as browser'), DB::raw('COUNT(*) as count'))
            ->whereBetween('last_seen_at', [$start->copy()->startOfDay(), $end->copy()->endOfDay()])
            ->groupBy('browser')
            ->orderByDesc('count')
            ->limit(5)
            ->get();

        $trendRecords = collect();

        if ($hasDailyStats) {
            $trendRecords = DB::table('analytics_daily_stats')
                ->whereBetween('date', [$start->toDateString(), $end->toDateString()])
                ->orderBy('date')
                ->get(['date', 'pageviews', 'visits']);
        }

        if ($trendRecords->isEmpty()) {
            $trendRecords = $this->buildFallbackTrend($start, $end);
        } else {
            $trendRecords = $this->fillMissingTrendDays($trendRecords, $start, $end);
        }

        $usersTotal = User::count();
        $activeUsers = $this->resolveActiveUsers();

        return view('admin.analysis.index', [
            'filters' => [
                'start' => $start->toDateString(),
                'end' => $end->toDateString(),
            ],
            'metrics' => [
                'pageviews' => $pageviews,
                'visits' => $visits,
                'unique_visitors' => $uniqueVisitors,
                'authenticated_users' => $authenticatedUsers,
                'total_users' => $usersTotal,
                'active_users' => $activeUsers,
            ],
            'topPages' => $topPages,
            'referrers' => $topReferrers,
            'deviceBreakdown' => $deviceBreakdown,
            'browserBreakdown' => $browserBreakdown,
            'trend' => [
                'labels' => $trendRecords->map(fn ($row) => Carbon::parse($row->date)->format('M j'))->toArray(),
                'pageviews' => $trendRecords->map(fn ($row) => (int) $row->pageviews)->toArray(),
                'visits' => $trendRecords->map(fn ($row) => (int) $row->visits)->toArray(),
            ],
        ]);
    }

    protected function resolveDate(?string $value, Carbon $fallback): Carbon
    {
        if (empty($value)) {
            return $fallback->copy()->startOfDay();
        }

        return Carbon::parse($value)->startOfDay();
    }

    protected function resolveActiveUsers(): int
    {
        $windowStart = Carbon::now()->subDays(self::ACTIVE_WINDOW_DAYS);

        $loggedInUsers = User::whereNotNull('last_login_at')
            ->where('last_login_at', '>=', $windowStart)
            ->pluck('id')
            ->toArray();

        $activityUsers = DB::table('analytics_pageviews')
            ->whereNotNull('user_id')
            ->where('created_at', '>=', $windowStart)
            ->distinct('user_id')
            ->pluck('user_id')
            ->toArray();

        return count(array_unique(array_merge($loggedInUsers, $activityUsers)));
    }

    protected function buildFallbackTrend(Carbon $start, Carbon $end): Collection
    {
        $pageviews = DB::table('analytics_pageviews')
            ->selectRaw('DATE(created_at) as date, COUNT(*) as pageviews')
            ->whereBetween('created_at', [$start->copy()->startOfDay(), $end->copy()->endOfDay()])
            ->groupBy('date')
            ->pluck('pageviews', 'date');

        $visits = DB::table('analytics_visits')
            ->selectRaw('DATE(first_seen_at) as date, COUNT(*) as visits')
            ->whereBetween('first_seen_at', [$start->copy()->startOfDay(), $end->copy()->endOfDay()])
            ->groupBy('date')
            ->pluck('visits', 'date');

        return $this->fillDateRange($start, $end)->map(fn (Carbon $day) => (object) [
            'date' => $day->toDateString(),
            'pageviews' => (int) ($pageviews[$day->toDateString()] ?? 0),
            'visits' => (int) ($visits[$day->toDateString()] ?? 0),
        ]);
    }

    protected function fillMissingTrendDays(Collection $records, Carbon $start, Carbon $end): Collection
    {
        $indexed = $records->keyBy('date');

        return $this->fillDateRange($start, $end)->map(fn (Carbon $day) => (object) [
            'date' => $day->toDateString(),
            'pageviews' => (int) ($indexed[$day->toDateString()]->pageviews ?? 0),
            'visits' => (int) ($indexed[$day->toDateString()]->visits ?? 0),
        ]);
    }

    protected function fillDateRange(Carbon $start, Carbon $end): Collection
    {
        $days = collect();
        $cursor = $start->copy();

        while ($cursor->lte($end)) {
            $days->push($cursor->copy());
            $cursor->addDay();
        }

        return $days;
    }
}
