<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Organization;
use Illuminate\Http\Request;

class OrganizationChartsController extends Controller
{
    public function index(Organization $organization, Request $request)
    {
        $this->authorize('view', $organization);

        $statsQuery = $organization->orgStats();

        if ($request->filled('year')) {
            $statsQuery->where('year', $request->year);
        }

        if ($request->filled('month')) {
            $statsQuery->where('month', $request->month);
        }

        $stats = $statsQuery->orderBy('dimension')->orderBy('segment')->get();

        $maleTotal = $stats->sum('male');
        $femaleTotal = $stats->sum('female');
        $otherTotal = $stats->sum('other');
        $totalEmployees = $maleTotal + $femaleTotal + $otherTotal;

        $genderDataset = [
            'labels' => ['Male', 'Female', 'Other'],
            'data' => [$maleTotal, $femaleTotal, $otherTotal],
        ];

        $dimensionBreakdown = $stats->groupBy('dimension')->mapWithKeys(function ($items, $dimension) {
            $segments = $items->groupBy('segment');
            $labels = [];
            $male = [];
            $female = [];
            $other = [];
            $totals = [];

            foreach ($segments as $segment => $group) {
                $labels[] = $segment;
                $segmentMale = $group->sum('male');
                $segmentFemale = $group->sum('female');
                $segmentOther = $group->sum('other');
                $segmentTotal = $segmentMale + $segmentFemale + $segmentOther;
                $male[] = $segmentMale;
                $female[] = $segmentFemale;
                $other[] = $segmentOther;
                $totals[] = $segmentTotal;
            }

            return [
                $dimension => [
                    'labels' => $labels,
                    'male' => $male,
                    'female' => $female,
                    'other' => $other,
                    'totals' => $totals,
                ],
            ];
        })->all();

        $selectedDimension = array_key_first($dimensionBreakdown) ?: null;

        $monthlyTrend = [];
        if ($request->filled('year')) {
            for ($month = 1; $month <= 12; $month++) {
                $monthlyTrend[$month] = $stats->where('month', $month)->sum(fn ($row) => $row->male + $row->female + $row->other);
            }
        }

        $availableYears = $organization->orgStats()
            ->whereNotNull('year')
            ->pluck('year')
            ->unique()
            ->sortDesc()
            ->values()
            ->all();

        return view('admin.organizations.charts', [
            'organization' => $organization,
            'genderDataset' => $genderDataset,
            'dimensionData' => $dimensionBreakdown,
            'selectedDimension' => $selectedDimension,
            'monthlyTrend' => $monthlyTrend,
            'filterYear' => $request->year,
            'filterMonth' => $request->month,
            'availableYears' => $availableYears,
            'summary' => [
                'total' => $totalEmployees,
                'male' => $maleTotal,
                'female' => $femaleTotal,
                'other' => $otherTotal,
            ],
            'hasStats' => $stats->isNotEmpty(),
        ]);
    }
}
