<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Actions\OrgStats\SummarizeOrgStatsAction;
use App\Models\OrgStat;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class PublicServantsController extends Controller
{
    public function index(Request $request, SummarizeOrgStatsAction $summaryAction): Response
    {
        $statsQuery = OrgStat::with('organization');
        if ($request->filled('year')) {
            $statsQuery->where('year', $request->year);
        }
        if ($request->filled('month')) {
            $statsQuery->where('month', $request->month);
        }

        $stats = $statsQuery->get();
        $summary = $summaryAction->execute($stats);

        $byOrganization = $stats->groupBy('organization_id')->map(function ($items) {
            $organization = $items->first()->organization;
            return [
                'organization' => $organization,
                'male' => $items->sum('male'),
                'female' => $items->sum('female'),
                'other' => $items->sum('other'),
                'total' => $items->sum(fn ($item) => $item->male + $item->female + $item->other),
                'year' => $items->pluck('year')->filter()->max(),
                'month' => $items->pluck('month')->filter()->max(),
            ];
        })->sortByDesc('total')->values()->all();

        return response()
            ->view('public-servants.index', [
                'filterYear' => $request->year,
                'filterMonth' => $request->month,
                'summary' => $summary,
                'organizations' => $byOrganization,
            ]);
    }
}
