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
        return redirect()->route('admin.organizations.show', $organization)->withInput($request->only(['year', 'month']));
    }

    public function store(StoreOrgStatRequest $request, Organization $organization): RedirectResponse
    {
        $organization->orgStats()->create($request->validated());

        return redirect()->route('admin.organizations.show', $organization)->with('success', 'Statistic recorded.')->withInput($request->only(['year', 'month']));
    }

    public function edit(Organization $organization, OrgStat $stat)
    {
        return view('admin.org-stats.edit', compact('organization', 'stat'));
    }

    public function update(UpdateOrgStatRequest $request, Organization $organization, OrgStat $stat): RedirectResponse
    {
        $stat->update($request->validated());

        return redirect()->route('admin.organizations.show', $organization)->with('success', 'Statistic updated.')->withInput($request->only(['year', 'month']));
    }

    public function destroy(Organization $organization, OrgStat $stat): RedirectResponse
    {
        $stat->delete();

        return redirect()->route('admin.organizations.show', $organization)->with('success', 'Statistic removed.');
    }
}
