<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Document;
use App\Models\Post;
use App\Models\Service;
use App\Models\Ticket;

use App\Models\CharterService;
use App\Models\Department;
use App\Models\Organization;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $filters = [
            'year' => $request->filled('year') ? (int) $request->input('year') : null,
            'month' => $request->filled('month') ? (int) $request->input('month') : null,
        ];

        $metrics = [
            'organizationTotal' => 0,
            'organizationActive' => 0,
            'departmentTotal' => 0,
            'departmentActive' => 0,
            'charterServicesTotal' => 0,
            'charterServicesActive' => 0,
            'publicServants' => [
                'total' => 0,
                'male' => 0,
                'female' => 0,
                'other' => 0,
            ],
            'newsPostsTotal' => 0,
            'visitsLast30Days' => 0,
            'uniqueVisitorsLast30Days' => 0,
            'pageviewsLast30Days' => 0,
            'usersTotal' => 0,
            'usersActiveLast30Days' => 0,
        ];

        $yearOptions = [];
        $topOrganizations = collect();
        $recentUpdates = collect();
        $topPages = collect();
        $recentVisits = collect();
        $analyticsPageviewsAvailable = false;
        $analyticsVisitsAvailable = false;

        $statsBaseFilters = function ($query) use ($filters) {
            if ($filters['year']) {
                $query->where('year', $filters['year']);
            }

            if ($filters['month']) {
                $query->where('month', $filters['month']);
            }
        };

        try {
            if (Schema::hasTable('organizations')) {
                $metrics['organizationTotal'] = DB::table('organizations')->count();
                $metrics['organizationActive'] = DB::table('organizations')->where('is_active', true)->count();
            }
        } catch (\Throwable $e) {
            //
        }

        try {
            if (Schema::hasTable('departments')) {
                $metrics['departmentTotal'] = DB::table('departments')->count();
                $metrics['departmentActive'] = DB::table('departments')->where('is_active', true)->count();
                $recentUpdates = $recentUpdates->concat(
                    DB::table('departments')
                        ->select('name as title', 'updated_at', DB::raw("'department' as type"))
                        ->orderByDesc('updated_at')
                        ->limit(10)
                        ->get()
                );
            }
        } catch (\Throwable $e) {
            //
        }

        try {
            if (Schema::hasTable('posts')) {
                $metrics['newsPostsTotal'] = DB::table('posts')->count();
            }
        } catch (\Throwable $e) {
            //
        }

        try {
            if (Schema::hasTable('charter_services')) {
                $metrics['charterServicesTotal'] = DB::table('charter_services')->count();
                $metrics['charterServicesActive'] = DB::table('charter_services')->where('is_active', true)->count();
                $recentUpdates = $recentUpdates->concat(
                    DB::table('charter_services')
                        ->select('name_am as title', 'updated_at', DB::raw("'charter_service' as type"))
                        ->orderByDesc('updated_at')
                        ->limit(10)
                        ->get()
                );
            }
        } catch (\Throwable $e) {
            //
        }

        try {
            if (Schema::hasTable('organizations')) {
                $recentUpdates = $recentUpdates->concat(
                    DB::table('organizations')
                        ->select('name', 'updated_at', DB::raw("'organization' as type"))
                        ->orderByDesc('updated_at')
                        ->limit(10)
                        ->get()
                );
            }
        } catch (\Throwable $e) {
            //
        }

        try {
            if (Schema::hasTable('org_stats')) {
                $yearOptions = DB::table('org_stats')
                    ->select('year')
                    ->whereNotNull('year')
                    ->distinct()
                    ->orderByDesc('year')
                    ->pluck('year')
                    ->filter()
                    ->values()
                    ->all();

                $summaryQuery = DB::table('org_stats');
                $statsBaseFilters($summaryQuery);
                $summary = $summaryQuery->select(
                    DB::raw('COALESCE(SUM(male), 0) as male'),
                    DB::raw('COALESCE(SUM(female), 0) as female'),
                    DB::raw('COALESCE(SUM(other), 0) as other')
                )->first();

                if ($summary) {
                    $metrics['publicServants']['male'] = (int) $summary->male;
                    $metrics['publicServants']['female'] = (int) $summary->female;
                    $metrics['publicServants']['other'] = (int) $summary->other;
                    $metrics['publicServants']['total'] = $metrics['publicServants']['male']
                        + $metrics['publicServants']['female']
                        + $metrics['publicServants']['other'];
                }

                if (Schema::hasTable('organizations')) {
                    $topOrganizations = DB::table('org_stats as s')
                        ->select(
                            'organizations.id as organization_id',
                            'organizations.name as organization_name',
                            DB::raw('COALESCE(SUM(s.male),0) as male'),
                            DB::raw('COALESCE(SUM(s.female),0) as female'),
                            DB::raw('COALESCE(SUM(s.other),0) as other'),
                            DB::raw('COALESCE(SUM(s.male + s.female + s.other),0) as total')
                        )
                        ->leftJoin('organizations', 'organizations.id', '=', 's.organization_id')
                        ->whereNotNull('organizations.id')
                        ->groupBy('organizations.id', 'organizations.name');

                    $statsBaseFilters($topOrganizations);

                    $topOrganizations = $topOrganizations
                        ->orderByDesc('total')
                        ->limit(10)
                        ->get();
                }
            }
        } catch (\Throwable $e) {
            //
        }

        $analyticsSince = Carbon::now()->subDays(30);

        try {
            if (Schema::hasTable('analytics_visits')) {
                $analyticsVisitsAvailable = true;
                $metrics['visitsLast30Days'] = DB::table('analytics_visits')
                    ->where('created_at', '>=', $analyticsSince)
                    ->count();
                $metrics['uniqueVisitorsLast30Days'] = DB::table('analytics_visits')
                    ->where('created_at', '>=', $analyticsSince)
                    ->distinct()
                    ->count('ip_hash');

                $recentVisits = DB::table('analytics_visits')
                    ->select('landing_path', 'first_seen_at', 'last_path')
                    ->orderByDesc('first_seen_at')
                    ->limit(5)
                    ->get();
            }
        } catch (\Throwable $e) {
            //
        }

        try {
            if (Schema::hasTable('analytics_pageviews')) {
                $analyticsPageviewsAvailable = true;
                $metrics['pageviewsLast30Days'] = DB::table('analytics_pageviews')
                    ->where('created_at', '>=', $analyticsSince)
                    ->count();

                $topPages = DB::table('analytics_pageviews')
                    ->select('path', DB::raw('COUNT(*) as hits'))
                    ->where('created_at', '>=', $analyticsSince)
                    ->groupBy('path')
                    ->orderByDesc('hits')
                    ->limit(10)
                    ->get();
            }
        } catch (\Throwable $e) {
            //
        }

        try {
            if (Schema::hasTable('users')) {
                $metrics['usersTotal'] = DB::table('users')->count();
                if ($analyticsPageviewsAvailable) {
                    $metrics['usersActiveLast30Days'] = DB::table('analytics_pageviews')
                        ->where('created_at', '>=', $analyticsSince)
                        ->whereNotNull('user_id')
                        ->distinct()
                        ->count('user_id');
                } elseif (Schema::hasColumn('users', 'last_login_at')) {
                    $metrics['usersActiveLast30Days'] = DB::table('users')
                        ->whereNotNull('last_login_at')
                        ->where('last_login_at', '>=', $analyticsSince)
                        ->count();
                }
            }
        } catch (\Throwable $e) {
            //
        }

        $recentUpdates = $recentUpdates
            ->sortByDesc(fn ($item) => $item->updated_at ?? now())
            ->values()
            ->slice(0, 10);

        return view('admin.dashboard', [
            'metrics' => $metrics,
            'filters' => $filters,
            'yearOptions' => $yearOptions,
            'topOrganizations' => $topOrganizations,
            'recentUpdates' => $recentUpdates,
            'analyticsPageviewsAvailable' => $analyticsPageviewsAvailable,
            'analyticsVisitsAvailable' => $analyticsVisitsAvailable,
            'topPages' => $topPages,
            'recentVisits' => $recentVisits,
        ]);
    }
}
