<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreRoleRequest;
use App\Http\Requests\UpdateRoleRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    private const ADMIN_ROLE_NAME = 'Admin';
    private const CRITICAL_PERMISSIONS = [
        'manage roles',
        'manage users',
    ];

    public function __construct()
    {
        $this->middleware('permission:manage roles');
    }

    public function index(): View
    {
        $roles = Role::withCount('permissions')->orderBy('name')->get();

        return view('admin.roles.index', compact('roles'));
    }

    public function create(): View
    {
        $permissions = Permission::orderBy('name')->get();

        return view('admin.roles.create', [
            'permissionGroups' => $this->groupPermissions($permissions),
        ]);
    }

    public function store(StoreRoleRequest $request): RedirectResponse
    {
        $role = Role::create(['name' => $request->input('name')]);
        $permissionIds = $this->sanitizePermissionsInput($request->input('permissions', []));

        if ($role->name === self::ADMIN_ROLE_NAME) {
            $permissionIds = $this->enforceCriticalPermissions($permissionIds);
        }

        $role->syncPermissions($permissionIds);

        return redirect()->route('admin.roles.index')->with('success', 'Role created successfully.');
    }

    public function edit(Role $role): View
    {
        $permissions = Permission::orderBy('name')->get();

        return view('admin.roles.edit', [
            'role' => $role,
            'permissionGroups' => $this->groupPermissions($permissions),
            'selectedPermissions' => $role->permissions->pluck('id')->map(fn ($id) => (int) $id)->toArray(),
        ]);
    }

    public function update(UpdateRoleRequest $request, Role $role): RedirectResponse
    {
        if ($role->name === self::ADMIN_ROLE_NAME && $request->input('name') !== self::ADMIN_ROLE_NAME) {
            return redirect()->back()->with('error', 'The Admin role cannot be renamed.');
        }

        $role->update(['name' => $request->input('name')]);
        $permissionIds = $this->sanitizePermissionsInput($request->input('permissions', []));

        if ($role->name === self::ADMIN_ROLE_NAME) {
            $permissionIds = $this->enforceCriticalPermissions($permissionIds);
        }

        $role->syncPermissions($permissionIds);

        return redirect()->route('admin.roles.index')->with('success', 'Role updated successfully.');
    }

    public function destroy(Role $role): RedirectResponse
    {
        if ($role->name === self::ADMIN_ROLE_NAME) {
            return redirect()->route('admin.roles.index')->with('error', 'The Admin role cannot be deleted.');
        }

        $role->delete();

        return redirect()->route('admin.roles.index')->with('success', 'Role deleted successfully.');
    }

    private function groupPermissions(Collection $permissions): Collection
    {
        return $permissions->groupBy(function (Permission $permission) {
            $parts = explode(' ', $permission->name, 2);

            $category = $parts[1] ?? $parts[0];

            return Str::title($category);
        });
    }

    private function sanitizePermissionsInput(array $permissions): array
    {
        return collect($permissions)
            ->filter(fn ($value) => is_numeric($value))
            ->map(fn ($value) => (int) $value)
            ->unique()
            ->values()
            ->toArray();
    }

    private function enforceCriticalPermissions(array $selectedPermissions): array
    {
        $criticalIds = Permission::whereIn('name', self::CRITICAL_PERMISSIONS)->pluck('id')->toArray();

        return array_values(array_unique(array_merge($selectedPermissions, $criticalIds)));
    }
}
