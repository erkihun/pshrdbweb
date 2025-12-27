<?php

namespace App\Http\Controllers;

use App\Actions\OrgStats\SummarizeOrgStatsAction;
use App\Models\Organization;
use Illuminate\Http\Request;

class PublicServantDashboardController extends Controller
{
    public function index(SummarizeOrgStatsAction $summaryAction)
    {
        $organizations = Organization::with('orgStats')->orderBy('name')->get();

        $summaries = $organizations->map(fn (Organization $organization) => [
            'name' => $organization->name,
            'code' => $organization->code,
            'total' => $organization->orgStats->sum(fn ($stat) => $stat->male + $stat->female + $stat->other),
            'male' => $organization->orgStats->sum('male'),
            'female' => $organization->orgStats->sum('female'),
            'other' => $organization->orgStats->sum('other'),
            'summary' => $summaryAction->execute($organization->orgStats),
        ]);

        $totals = [
            'male' => $summaries->sum('male'),
            'female' => $summaries->sum('female'),
            'other' => $summaries->sum('other'),
        ];
        $totals['total'] = $totals['male'] + $totals['female'] + $totals['other'];

        return view('employee-statistics.show', compact('summaries', 'totals'));
    }
}
