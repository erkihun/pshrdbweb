<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Actions\OrgStats\CreateOrgSnapshotAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreOrgStatSnapshotRequest;
use App\Models\Organization;
use App\Models\OrgStatSnapshot;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;

class OrgStatSnapshotController extends Controller
{
    public function store(
        StoreOrgStatSnapshotRequest $request,
        Organization $organization,
        CreateOrgSnapshotAction $action
    ): RedirectResponse {
        $this->authorize('update', $organization);

        $data = $request->validated();

        $action->execute(
            $organization,
            $data['period_type'],
            $data['year'] ?? null,
            $data['month'] ?? null,
            $data['quarter'] ?? null,
            $request->user()?->id
        );

        return redirect()
            ->route('admin.organizations.show', $organization)
            ->with('success', 'Snapshot created.');
    }

    public function show(Organization $organization, OrgStatSnapshot $snapshot): JsonResponse
    {
        $this->authorize('view', $organization);

        if ($snapshot->organization_id !== $organization->id) {
            abort(404);
        }

        $payload = [
            'organization' => $organization->name,
            'period_type' => $snapshot->period_type,
            'year' => $snapshot->year,
            'month' => $snapshot->month,
            'quarter' => $snapshot->quarter,
            'totals' => $snapshot->totals,
            'breakdown' => $snapshot->breakdown,
            'created_at' => $snapshot->created_at->toIso8601String(),
        ];

        return response()
            ->json($payload, 200, [
                'Content-Disposition' => sprintf('attachment; filename="snapshot-%s.json"', $snapshot->uuid),
            ]);
    }
}
