<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Vacancy;
use App\Models\VacancyApplication;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class VacancyAnalyticsController extends Controller
{
    public function index(Request $request): View
    {
        $filters = [
            'from' => $request->input('from'),
            'to' => $request->input('to'),
            'vacancy' => $request->input('vacancy'),
            'status' => $request->input('status'),
        ];

        $cacheKey = 'vacancy.analytics.' . md5(json_encode($filters));

        $data = Cache::remember($cacheKey, now()->addMinutes(15), function () use ($filters) {
            $applyFilters = function ($query, bool $withTablePrefix = false) use ($filters) {
                $prefix = $withTablePrefix ? 'vacancy_applications.' : '';

                if ($filters['vacancy']) {
                    $query->where($prefix . 'vacancy_id', $filters['vacancy']);
                }
                if ($filters['status']) {
                    $query->where($prefix . 'status', $filters['status']);
                }
                if ($filters['from']) {
                    $query->whereDate($prefix . 'created_at', '>=', $filters['from']);
                }
                if ($filters['to']) {
                    $query->whereDate($prefix . 'created_at', '<=', $filters['to']);
                }

                return $query;
            };

            $vacancies = Vacancy::query()
                ->orderBy('title')
                ->get(['id', 'title']);

            $baseApplications = $applyFilters(VacancyApplication::query());

            $statusCounts = (clone $baseApplications)
                ->selectRaw('status, count(*) as total')
                ->groupBy('status')
                ->pluck('total', 'status');

            $genderCounts = (clone $baseApplications)
                ->whereNotNull('gender')
                ->selectRaw('gender, count(*) as total')
                ->groupBy('gender')
                ->pluck('total', 'gender');

            $avgGpa = (clone $baseApplications)
                ->whereNotNull('gpa')
                ->avg('gpa');

            $ageBuckets = $applyFilters(
                VacancyApplication::query()
                    ->selectRaw(
                        "CASE
                            WHEN TIMESTAMPDIFF(YEAR, date_of_birth, CURDATE()) <= 25 THEN '18-25'
                            WHEN TIMESTAMPDIFF(YEAR, date_of_birth, CURDATE()) BETWEEN 26 AND 35 THEN '26-35'
                            WHEN TIMESTAMPDIFF(YEAR, date_of_birth, CURDATE()) BETWEEN 36 AND 45 THEN '36-45'
                            ELSE '46+'
                        END as bucket,
                        count(*) as total"
                    )
                    ->whereNotNull('date_of_birth'),
                true
            )
                ->groupBy('bucket')
                ->pluck('total', 'bucket');

            $kpis = [
                'total_vacancies' => Vacancy::count(),
                'total_applications' => (clone $baseApplications)->count(),
                'applications_today' => (clone $baseApplications)->whereDate('created_at', today())->count(),
                'applications_month' => (clone $baseApplications)
                    ->whereYear('created_at', now()->year)
                    ->whereMonth('created_at', now()->month)
                    ->count(),
                'shortlisted' => (int) ($statusCounts[VacancyApplication::STATUS_SHORTLISTED] ?? 0),
                'rejected' => (int) ($statusCounts[VacancyApplication::STATUS_REJECTED] ?? 0),
                'hired' => (int) ($statusCounts[VacancyApplication::STATUS_HIRED] ?? 0),
            ];

            $rangeStart = $filters['from']
                ? Carbon::parse($filters['from'])->startOfDay()
                : now()->subDays(29)->startOfDay();
            $rangeEnd = $filters['to']
                ? Carbon::parse($filters['to'])->endOfDay()
                : now()->endOfDay();

            $trendRows = $applyFilters(VacancyApplication::query())
                ->whereBetween('created_at', [$rangeStart, $rangeEnd])
                ->selectRaw('DATE(created_at) as date, count(*) as total')
                ->groupBy('date')
                ->orderBy('date')
                ->get();

            $trendIndex = $trendRows->pluck('total', 'date')->toArray();
            $trendLabels = [];
            $trendTotals = [];
            $cursor = $rangeStart->copy();

            while ($cursor->lte($rangeEnd)) {
                $dateKey = $cursor->toDateString();
                $trendLabels[] = $cursor->format('M d');
                $trendTotals[] = (int) ($trendIndex[$dateKey] ?? 0);
                $cursor->addDay();
            }

            $statusChartLabels = [
                VacancyApplication::STATUS_SUBMITTED,
                VacancyApplication::STATUS_SHORTLISTED,
                VacancyApplication::STATUS_REJECTED,
                VacancyApplication::STATUS_HIRED,
                VacancyApplication::STATUS_WITHDRAWN,
            ];
            $statusChartValues = array_map(
                fn ($status) => (int) ($statusCounts[$status] ?? 0),
                $statusChartLabels
            );

            $perVacancyRows = $applyFilters(
                VacancyApplication::query()
                    ->select('vacancies.title as label', DB::raw('count(*) as total'))
                    ->join('vacancies', 'vacancies.id', '=', 'vacancy_applications.vacancy_id'),
                true
            )
                ->groupBy('vacancies.title')
                ->orderByDesc('total')
                ->get();

            return [
                'vacancies' => $vacancies,
                'kpis' => $kpis,
                'charts' => [
                    'trend' => [
                        'labels' => $trendLabels,
                        'values' => $trendTotals,
                    ],
                    'status' => [
                        'labels' => $statusChartLabels,
                        'values' => $statusChartValues,
                    ],
                    'perVacancy' => [
                        'labels' => $perVacancyRows->pluck('label')->toArray(),
                        'values' => $perVacancyRows->pluck('total')->map(fn ($value) => (int) $value)->toArray(),
                    ],
                ],
                'insights' => [
                    'gender' => [
                        'male' => (int) ($genderCounts['male'] ?? 0),
                        'female' => (int) ($genderCounts['female'] ?? 0),
                    ],
                    'avg_gpa' => $avgGpa ? round($avgGpa, 2) : null,
                    'age_buckets' => [
                        '18-25' => (int) ($ageBuckets['18-25'] ?? 0),
                        '26-35' => (int) ($ageBuckets['26-35'] ?? 0),
                        '36-45' => (int) ($ageBuckets['36-45'] ?? 0),
                        '46+' => (int) ($ageBuckets['46+'] ?? 0),
                    ],
                ],
                'filters' => $filters,
            ];
        });

        if (! isset($data['insights'])) {
            $data['insights'] = [
                'gender' => [
                    'male' => 0,
                    'female' => 0,
                ],
                'avg_gpa' => null,
                'age_buckets' => [
                    '18-25' => 0,
                    '26-35' => 0,
                    '36-45' => 0,
                    '46+' => 0,
                ],
            ];
        }

        return view('admin.vacancies.analytics', $data);
    }
}
