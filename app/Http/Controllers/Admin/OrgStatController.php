<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreOrgStatRequest;
use App\Http\Requests\UpdateOrgStatRequest;
use App\Models\OrgStat;
use App\Models\Organization;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;

class OrgStatController extends Controller
{
    public function index(Organization $organization, Request $request)
    {
        $this->authorize('view', $organization);

        return redirect()->route('admin.organizations.show', $organization)->withInput($request->only(['year', 'month']));
    }

    public function store(StoreOrgStatRequest $request, Organization $organization): RedirectResponse
    {
        $this->authorize('update', $organization);

        $organization->orgStats()->create($request->validated());

        return redirect()->route('admin.organizations.show', $organization)->with('success', 'Statistic recorded.')->withInput($request->only(['year', 'month']));
    }

    public function edit(Organization $organization, OrgStat $stat)
    {
        $this->authorize('view', $organization);
        abort_if($stat->organization_id !== $organization->id, 404);

        $savedSegments = $organization->orgStats()
            ->latest('updated_at')
            ->latest('id')
            ->limit(12)
            ->get();

        return view('admin.org-stats.edit', compact('organization', 'stat', 'savedSegments'));
    }

    public function update(UpdateOrgStatRequest $request, Organization $organization, OrgStat $stat): RedirectResponse
    {
        $this->authorize('update', $organization);
        abort_if($stat->organization_id !== $organization->id, 404);

        $stat->update($request->validated());

        return redirect()->route('admin.organizations.show', $organization)->with('success', 'Statistic updated.')->withInput($request->only(['year', 'month']));
    }

    public function destroy(Organization $organization, OrgStat $stat): RedirectResponse
    {
        $this->authorize('update', $organization);
        abort_if($stat->organization_id !== $organization->id, 404);

        $stat->delete();

        return redirect()->route('admin.organizations.show', $organization)->with('success', 'Statistic removed.');
    }
}
