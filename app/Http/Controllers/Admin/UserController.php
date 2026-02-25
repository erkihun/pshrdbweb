<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\Department;
use App\Models\Organization;
use App\Models\User;
use App\Services\AuditLogService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with(['roles', 'permissions'])->paginate(15);

        return view('admin.users.index', compact('users'));
    }

    public function create(): View
    {
        $roles = Role::orderBy('name')->get();
        $departments = Department::orderBy('name_en')->get();
        $organizations = Organization::orderBy('name')->get();

        return view('admin.users.create', compact('roles', 'departments', 'organizations'));
    }

    public function store(StoreUserRequest $request): RedirectResponse
    {
        $data = $request->validated();

        $attributes = [
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'phone' => $data['phone'] ?? null,
            'national_id' => $data['national_id'] ?? null,
            'gender' => $data['gender'] ?? null,
            'department_id' => $data['department_id'] ?? null,
            'organization_id' => $data['organization_id'] ?? null,
        ];

        if ($request->hasFile('avatar')) {
            $attributes['avatar_path'] = $request->file('avatar')->store('users/avatars', 'public');
        }

        $user = User::create($attributes);

        if (!empty($data['roles'])) {
            $user->syncRoles($data['roles']);
        }

        AuditLogService::log('users.created', 'user', (string) $user->id, [
            'user_email' => $user->email,
            'roles' => $user->roles->pluck('name')->all(),
        ]);

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'User created.');
    }

    public function edit(User $user)
    {
        $roles = Role::orderBy('name')->get();
        $departments = Department::orderBy('name_en')->get();
        $organizations = Organization::orderBy('name')->get();

        return view('admin.users.edit', compact('user', 'roles', 'departments', 'organizations'));
    }

    public function update(UpdateUserRequest $request, User $user)
    {
        $data = $request->validated();
        $attributes = [
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'] ?? null,
            'national_id' => $data['national_id'] ?? null,
            'gender' => $data['gender'] ?? null,
            'department_id' => $data['department_id'] ?? null,
            'organization_id' => $data['organization_id'] ?? null,
        ];

        if (! empty($data['password'])) {
            $attributes['password'] = Hash::make($data['password']);
        }

        if ($request->hasFile('avatar')) {
            $attributes['avatar_path'] = $request->file('avatar')->store('users/avatars', 'public');
        }

        $user->update($attributes);
        $user->syncRoles($data['roles'] ?? []);

        AuditLogService::log('users.updated', 'user', (string) $user->id, [
            'user_email' => $user->email,
            'roles' => $user->roles->pluck('name')->all(),
        ]);

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'User updated.');
    }
}
