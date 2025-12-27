<?php

declare(strict_types=1);

namespace App\Actions\OrgStats;

use App\Models\OrgStatSnapshot;
use App\Models\Organization;
use Illuminate\Support\Collection;

final class CreateOrgSnapshotAction
{
    private const QUARTER_MONTH_MAP = [
        1 => [1, 2, 3],
        2 => [4, 5, 6],
        3 => [7, 8, 9],
        4 => [10, 11, 12],
    ];

    public function execute(
        Organization $organization,
        string $periodType,
        ?int $year = null,
        ?int $month = null,
        ?int $quarter = null,
        ?int $createdBy = null
    ): OrgStatSnapshot {
        $query = $organization->orgStats();

        if ($year !== null) {
            $query->where('year', $year);
        }

        if ($periodType === 'monthly' && $month !== null) {
            $query->where('month', $month);
        }

        if ($periodType === 'quarterly' && $quarter !== null && isset(self::QUARTER_MONTH_MAP[$quarter])) {
            $query->whereIn('month', self::QUARTER_MONTH_MAP[$quarter]);
        }

        $stats = $query->get();
        $totals = $this->calculateTotals($stats);
        $breakdown = $this->buildBreakdown($stats);

        return OrgStatSnapshot::updateOrCreate(
            [
                'organization_id' => $organization->id,
                'period_type' => $periodType,
                'year' => $year,
                'month' => $periodType === 'monthly' ? $month : null,
                'quarter' => $periodType === 'quarterly' ? $quarter : null,
            ],
            [
                'totals' => $totals,
                'breakdown' => $breakdown,
                'created_by' => $createdBy,
            ]
        );
    }

    private function calculateTotals(Collection $stats): array
    {
        $male = $stats->sum('male');
        $female = $stats->sum('female');
        $other = $stats->sum('other');

        return [
            'male' => $male,
            'female' => $female,
            'other' => $other,
            'total' => $male + $female + $other,
        ];
    }

    private function buildBreakdown(Collection $stats): array
    {
        $breakdown = [];

        foreach ($stats as $stat) {
            $dimension = $stat->dimension;
            $segment = $stat->segment;
            $segmentData = $breakdown[$dimension][$segment] ?? [
                'male' => 0,
                'female' => 0,
                'other' => 0,
                'total' => 0,
            ];

            $segmentData['male'] += $stat->male;
            $segmentData['female'] += $stat->female;
            $segmentData['other'] += $stat->other;
            $segmentData['total'] += $stat->male + $stat->female + $stat->other;

            $breakdown[$dimension][$segment] = $segmentData;
        }

        return $breakdown;
    }
}
