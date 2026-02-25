<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\OrgStat;
use App\Models\OrgStatSnapshot;
use App\Models\Organization;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class AnalyticsController extends Controller
{
    private const QUARTER_MONTH_MAP = [
        1 => [1, 2, 3],
        2 => [4, 5, 6],
        3 => [7, 8, 9],
        4 => [10, 11, 12],
    ];

    public function index(Request $request)
    {
        $periodType = $request->get('period_type', 'monthly');
        $year = $request->integer('year');
        $month = $request->integer('month');
        $quarter = $request->integer('quarter');
        $selectedDimension = $request->get('dimension');

        $snapshotQuery = OrgStatSnapshot::with('organization')->where('period_type', $periodType);
        $this->applyPeriodFiltersToSnapshot($snapshotQuery, $periodType, $year, $month, $quarter);
        $snapshots = $snapshotQuery->get();

        if ($snapshots->isNotEmpty()) {
            $aggregate = $this->aggregateSnapshots($snapshots);
        } else {
            $stats = $this->loadLiveStats($periodType, $year, $month, $quarter);
            $aggregate = $this->aggregateStats($stats);
        }

        $dimensionChartData = $this->buildDimensionChartPayload($aggregate['dimensionGroups']);
        $dimensionOptions = array_keys($dimensionChartData);
        $selectedDimension = $selectedDimension ?? ($dimensionOptions[0] ?? null);
        $selectedDimensionSegments = $dimensionChartData[$selectedDimension]['segments'] ?? [];
        $monthlyTrend = $this->buildMonthlyTrend($year);

        return view('admin.analysis.index', [
            'summary' => $aggregate['totals'],
            'genderChart' => [
                'labels' => ['Male', 'Female', 'Other'],
                'data' => [
                    $aggregate['totals']['male'] ?? 0,
                    $aggregate['totals']['female'] ?? 0,
                    $aggregate['totals']['other'] ?? 0,
                ],
            ],
            'dimensionChartData' => $dimensionChartData,
            'selectedDimension' => $selectedDimension,
            'selectedDimensionSegments' => $selectedDimensionSegments,
            'dimensionOptions' => $dimensionOptions,
            'topOrganizations' => $aggregate['topOrganizations'],
            'monthlyTrend' => $monthlyTrend,
            'activeOrganizations' => Organization::where('is_active', true)->count(),
            'filters' => [
                'period_type' => $periodType,
                'year' => $year,
                'month' => $month,
                'quarter' => $quarter,
                'dimension' => $selectedDimension,
            ],
        ]);
    }

    private function applyPeriodFiltersToSnapshot($query, string $periodType, ?int $year, ?int $month, ?int $quarter): void
    {
        if ($year !== null) {
            $query->where('year', $year);
        }

        if ($periodType === 'monthly' && $month !== null) {
            $query->where('month', $month);
        }

        if ($periodType === 'quarterly' && $quarter !== null && isset(self::QUARTER_MONTH_MAP[$quarter])) {
            $query->where('quarter', $quarter);
        }
    }

    private function loadLiveStats(string $periodType, ?int $year, ?int $month, ?int $quarter): Collection
    {
        $query = OrgStat::with('organization');

        if ($year !== null) {
            $query->where('year', $year);
        }

        if ($periodType === 'monthly' && $month !== null) {
            $query->where('month', $month);
        }

        if ($periodType === 'quarterly' && $quarter !== null && isset(self::QUARTER_MONTH_MAP[$quarter])) {
            $query->whereIn('month', self::QUARTER_MONTH_MAP[$quarter]);
        }

        return $query->get();
    }

    private function aggregateSnapshots(Collection $snapshots): array
    {
        $totals = ['male' => 0, 'female' => 0, 'other' => 0, 'total' => 0];
        $dimensionGroups = [];
        $organizationTotals = [];

        foreach ($snapshots as $snapshot) {
            $snapshotTotals = $snapshot->totals ?? ['male' => 0, 'female' => 0, 'other' => 0, 'total' => 0];

            foreach ($totals as $metric => $_) {
                $totals[$metric] += $snapshotTotals[$metric] ?? 0;
            }

            $orgId = $snapshot->organization_id;
            $organizationTotals[$orgId]['name'] = $snapshot->organization?->name ?? 'Organization';
            $organizationTotals[$orgId]['male'] = ($organizationTotals[$orgId]['male'] ?? 0) + ($snapshotTotals['male'] ?? 0);
            $organizationTotals[$orgId]['female'] = ($organizationTotals[$orgId]['female'] ?? 0) + ($snapshotTotals['female'] ?? 0);
            $organizationTotals[$orgId]['other'] = ($organizationTotals[$orgId]['other'] ?? 0) + ($snapshotTotals['other'] ?? 0);
            $organizationTotals[$orgId]['total'] = ($organizationTotals[$orgId]['total'] ?? 0) + ($snapshotTotals['total'] ?? (($snapshotTotals['male'] ?? 0) + ($snapshotTotals['female'] ?? 0) + ($snapshotTotals['other'] ?? 0)));

            foreach ($snapshot->breakdown ?? [] as $dimension => $segments) {
                foreach ($segments as $segment => $values) {
                    $this->accumulateDimensionSegment($dimensionGroups, $dimension, $segment, $values);
                }
            }
        }

        $topOrganizations = collect($organizationTotals)
            ->sortByDesc('total')
            ->values()
            ->take(10)
            ->all();

        return compact('totals', 'dimensionGroups', 'topOrganizations');
    }

    private function aggregateStats(Collection $stats): array
    {
        $totals = ['male' => 0, 'female' => 0, 'other' => 0, 'total' => 0];
        $dimensionGroups = [];
        $organizationTotals = [];

        foreach ($stats as $stat) {
            $statTotal = $stat->male + $stat->female + $stat->other;
            $totals['male'] += $stat->male;
            $totals['female'] += $stat->female;
            $totals['other'] += $stat->other;
            $totals['total'] += $statTotal;

            $orgId = $stat->organization_id;
            $organizationTotals[$orgId]['name'] = $stat->organization?->name ?? 'Organization';
            $organizationTotals[$orgId]['male'] = ($organizationTotals[$orgId]['male'] ?? 0) + $stat->male;
            $organizationTotals[$orgId]['female'] = ($organizationTotals[$orgId]['female'] ?? 0) + $stat->female;
            $organizationTotals[$orgId]['other'] = ($organizationTotals[$orgId]['other'] ?? 0) + $stat->other;
            $organizationTotals[$orgId]['total'] = ($organizationTotals[$orgId]['total'] ?? 0) + $statTotal;

            $this->accumulateDimensionSegment($dimensionGroups, $stat->dimension, $stat->segment, [
                'male' => $stat->male,
                'female' => $stat->female,
                'other' => $stat->other,
                'total' => $statTotal,
            ]);
        }

        $topOrganizations = collect($organizationTotals)
            ->sortByDesc('total')
            ->values()
            ->take(10)
            ->all();

        return compact('totals', 'dimensionGroups', 'topOrganizations');
    }

    private function accumulateDimensionSegment(array &$groups, string $dimension, string $segment, array $values): void
    {
        $entry = $groups[$dimension][$segment] ?? [
            'male' => 0,
            'female' => 0,
            'other' => 0,
            'total' => 0,
        ];

        foreach (['male', 'female', 'other'] as $metric) {
            $entry[$metric] += $values[$metric] ?? 0;
        }

        $valueTotal = $values['total'] ?? (($values['male'] ?? 0) + ($values['female'] ?? 0) + ($values['other'] ?? 0));
        $entry['total'] += $valueTotal;

        $groups[$dimension][$segment] = $entry;
    }

    private function buildDimensionChartPayload(array $dimensionGroups): array
    {
        $payload = [];

        foreach ($dimensionGroups as $dimension => $segments) {
            $labels = [];
            $male = [];
            $female = [];
            $other = [];
            $totals = [];
            $segmentDetails = [];

            foreach ($segments as $segment => $values) {
                $labels[] = $segment;
                $male[] = $values['male'];
                $female[] = $values['female'];
                $other[] = $values['other'];
                $totals[] = $values['total'];
                $segmentDetails[] = [
                    'segment' => $segment,
                    'male' => $values['male'],
                    'female' => $values['female'],
                    'other' => $values['other'],
                    'total' => $values['total'],
                ];
            }

            $payload[$dimension] = [
                'labels' => $labels,
                'male' => $male,
                'female' => $female,
                'other' => $other,
                'totals' => $totals,
                'segments' => $segmentDetails,
            ];
        }

        return $payload;
    }

    private function buildMonthlyTrend(?int $year): array
    {
        if ($year === null) {
            return [
                'labels' => [],
                'data' => [],
                'hasData' => false,
            ];
        }

        $monthlyTotals = array_fill(1, 12, 0);
        $snapshots = OrgStatSnapshot::where('period_type', 'monthly')
            ->where('year', $year)
            ->get();

        if ($snapshots->isNotEmpty()) {
            foreach ($snapshots as $snapshot) {
                if ($snapshot->month !== null) {
                    $monthlyTotals[$snapshot->month] += $snapshot->totals['total'] ?? 0;
                }
            }
        } else {
            $stats = OrgStat::where('year', $year)->whereNotNull('month')->get();
            foreach ($stats as $stat) {
                if ($stat->month !== null) {
                    $monthlyTotals[$stat->month] += $stat->male + $stat->female + $stat->other;
                }
            }
        }

        $labels = array_map(fn ($month) => DateTime::createFromFormat('!m', (string) $month)->format('M'), range(1, 12));
        $hasData = array_sum($monthlyTotals) > 0;

        return [
            'labels' => $labels,
            'data' => array_values($monthlyTotals),
            'hasData' => $hasData,
        ];
    }
}
