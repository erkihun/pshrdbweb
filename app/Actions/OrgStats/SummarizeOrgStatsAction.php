<?php

declare(strict_types=1);

namespace App\Actions\OrgStats;

use Illuminate\Support\Collection;

final class SummarizeOrgStatsAction
{
    public function execute(Collection $stats): array
    {
        $byDimension = $stats->groupBy('dimension')->map(function (Collection $items) {
            $male = $items->sum('male');
            $female = $items->sum('female');
            $other = $items->sum('other');

            return [
                'male' => $male,
                'female' => $female,
                'other' => $other,
                'total' => $male + $female + $other,
                'segments' => $items->map(function ($stat) {
                    return [
                        'segment' => $stat->segment,
                        'male' => $stat->male,
                        'female' => $stat->female,
                        'other' => $stat->other,
                        'total' => $stat->male + $stat->female + $stat->other,
                        'year' => $stat->year,
                        'month' => $stat->month,
                        'id' => $stat->id,
                    ];
                })->values(),
            ];
        });

        $male = $stats->sum('male');
        $female = $stats->sum('female');
        $other = $stats->sum('other');

        return [
            'by_dimension' => $byDimension->all(),
            'total' => [
                'male' => $male,
                'female' => $female,
                'other' => $other,
                'total' => $male + $female + $other,
            ],
        ];
    }
}
