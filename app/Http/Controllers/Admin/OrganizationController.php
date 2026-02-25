<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Actions\OrgStats\SummarizeOrgStatsAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreOrganizationRequest;
use App\Http\Requests\UpdateOrganizationRequest;
use App\Models\Organization;
use Illuminate\Http\Request;

class OrganizationController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Organization::class, 'organization');
    }

    public function index(Request $request)
    {
        $query = Organization::query();

        if (! $request->user()?->can('manage organizations')) {
            $query->whereKey($request->user()?->organization_id);
        }

        if ($request->filled('q')) {
            $query->where('name', 'like', '%' . $request->q . '%');
        }
        $organizations = $query->withCount('orgStats')->orderByDesc('created_at')->paginate(15)->withQueryString();

        return view('admin.organizations.index', compact('organizations'));
    }

    public function create()
    {
        return view('admin.organizations.create');
    }

    public function store(StoreOrganizationRequest $request)
    {
        Organization::create($request->validated());

        return redirect()->route('admin.organizations.index')->with('success', 'Organization created.');
    }

    public function show(Organization $organization, Request $request, SummarizeOrgStatsAction $summaryAction)
    {
        $statsQuery = $organization->orgStats();
        if ($request->filled('year')) {
            $statsQuery->where('year', $request->year);
        }
        if ($request->filled('month')) {
            $statsQuery->where('month', $request->month);
        }

        $stats = $statsQuery->orderBy('dimension')->orderBy('segment')->get();
        $summary = $summaryAction->execute($stats);

        $activeTab = in_array($request->query('tab'), ['overview', 'stats'], true)
            ? $request->query('tab')
            : 'overview';

        return view('admin.organizations.show', [
            'organization' => $organization,
            'stats' => $stats,
            'summary' => $summary,
            'filterYear' => $request->year,
            'filterMonth' => $request->month,
            'activeTab' => $activeTab,
        ]);
    }

    public function edit(Organization $organization)
    {
        return view('admin.organizations.edit', compact('organization'));
    }

    public function update(UpdateOrganizationRequest $request, Organization $organization)
    {
        $organization->update($request->validated());

        return redirect()->route('admin.organizations.index')->with('success', 'Organization updated.');
    }

    public function destroy(Organization $organization)
    {
        $organization->delete();

        return back()->with('success', 'Organization removed.');
    }
}
