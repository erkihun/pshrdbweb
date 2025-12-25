@extends('admin.layouts.app')

@section('content')
    <div class="min-h-screen bg-slate-50 py-8">
        <div class="mx-auto max-w-4xl px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-8">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-3xl font-bold text-slate-900">{{ __('common.actions.edit') }}</h1>
                        <div class="mt-2 flex items-center gap-2">
                            <div class="flex h-10 w-10 items-center justify-center rounded-full bg-slate-100">
                                <svg class="h-5 w-5 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                            </div>
                            <div>
                                <p class="font-medium text-slate-900">{{ $user->name }}</p>
                                <p class="text-sm text-slate-500">{{ $user->email }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="text-sm">
                        <span class="inline-flex items-center rounded-full bg-slate-100 px-3 py-1 text-xs font-medium text-slate-800">
                            User ID: {{ $user->id }}
                        </span>
                    </div>
                </div>
                <div class="mt-4 border-t border-slate-200 pt-4">
                    <p class="text-sm text-slate-600">Manage user permissions and roles to control access levels within the system.</p>
                </div>
            </div>

            <!-- Main Form -->
            <form
                method="POST"
                action="{{ route('admin.users.update', $user) }}"
                class="space-y-8"
                id="user-permissions-form"
            >
                @csrf
                @method('PUT')

                <!-- Roles Section -->
                <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
                    <div class="border-b border-slate-100 bg-slate-50/50 px-6 py-4">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-indigo-100">
                                    <svg class="h-4 w-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h2 class="text-lg font-semibold text-slate-900">{{ __('common.labels.roles') }}</h2>
                                    <p class="text-sm text-slate-500">Assign system roles to define user capabilities</p>
                                </div>
                            </div>
                            <button
                                type="button"
                                onclick="toggleAllRoles()"
                                class="inline-flex items-center gap-2 rounded-lg border border-slate-300 px-3 py-1.5 text-xs font-medium text-slate-700 transition hover:bg-slate-100"
                            >
                                <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                Toggle All
                            </button>
                        </div>
                    </div>
                    <div class="p-6">
                        <div class="grid gap-3 sm:grid-cols-2 lg:grid-cols-3">
                            @foreach ($roles as $role)
                                <label class="group relative flex cursor-pointer items-start rounded-lg border border-slate-200 p-4 transition-all hover:border-slate-300 hover:bg-slate-50/50 has-[:checked]:border-indigo-300 has-[:checked]:bg-indigo-50/50">
                                    <div class="flex h-5 items-center">
                                        <input
                                            type="checkbox"
                                            name="roles[]"
                                            value="{{ $role->name }}"
                                            class="role-checkbox h-4 w-4 rounded border-slate-300 text-indigo-600 focus:ring-indigo-500 focus:ring-offset-0"
                                            @checked($user->roles->contains('name', $role->name))
                                        >
                                    </div>
                                    <div class="ml-3 flex-1">
                                        <span class="text-sm font-medium text-slate-900">{{ $role->name }}</span>
                                        <p class="mt-1 text-xs text-slate-500">Role permissions and access levels</p>
                                    </div>
                                    <div class="absolute right-3 top-3 opacity-0 transition-opacity group-hover:opacity-100">
                                        <svg class="h-4 w-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                        </svg>
                                    </div>
                                </label>
                            @endforeach
                        </div>
                        @error('roles')
                            <div class="mt-4 flex items-center gap-2 rounded-lg bg-rose-50 p-3">
                                <svg class="h-4 w-4 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <p class="text-sm text-rose-700">{{ $message }}</p>
                            </div>
                        @enderror
                    </div>
                </div>

                <!-- Permissions Section -->
                <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
                    <div class="border-b border-slate-100 bg-slate-50/50 px-6 py-4">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-emerald-100">
                                    <svg class="h-4 w-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h2 class="text-lg font-semibold text-slate-900">{{ __('common.labels.permissions') }}</h2>
                                    <p class="text-sm text-slate-500">Granular control over specific user actions</p>
                                </div>
                            </div>
                            <button
                                type="button"
                                onclick="toggleAllPermissions()"
                                class="inline-flex items-center gap-2 rounded-lg border border-slate-300 px-3 py-1.5 text-xs font-medium text-slate-700 transition hover:bg-slate-100"
                            >
                                <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                Toggle All
                            </button>
                        </div>
                    </div>
                    <div class="p-6">
                        <div class="grid gap-3 sm:grid-cols-2">
                            @foreach ($permissions as $permission)
                                <label class="group relative flex cursor-pointer items-start rounded-lg border border-slate-200 p-4 transition-all hover:border-slate-300 hover:bg-slate-50/50 has-[:checked]:border-emerald-300 has-[:checked]:bg-emerald-50/50">
                                    <div class="flex h-5 items-center">
                                        <input
                                            type="checkbox"
                                            name="permissions[]"
                                            value="{{ $permission->name }}"
                                            class="permission-checkbox h-4 w-4 rounded border-slate-300 text-emerald-600 focus:ring-emerald-500 focus:ring-offset-0"
                                            @checked($user->permissions->contains('name', $permission->name))
                                        >
                                    </div>
                                    <div class="ml-3 flex-1">
                                        <span class="text-sm font-medium text-slate-900">{{ $permission->name }}</span>
                                        <p class="mt-1 text-xs text-slate-500">Direct permission assignment</p>
                                    </div>
                                    <div class="absolute right-3 top-3 opacity-0 transition-opacity group-hover:opacity-100">
                                        <svg class="h-4 w-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                        </svg>
                                    </div>
                                </label>
                            @endforeach
                        </div>
                        @error('permissions')
                            <div class="mt-4 flex items-center gap-2 rounded-lg bg-rose-50 p-3">
                                <svg class="h-4 w-4 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <p class="text-sm text-rose-700">{{ $message }}</p>
                            </div>
                        @enderror
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex items-center justify-between rounded-xl border border-slate-200 bg-white px-6 py-4 shadow-sm">
                    <a 
                        href="{{ route('admin.users.index') }}" 
                        class="inline-flex items-center gap-2 rounded-lg px-4 py-2 text-sm font-medium text-slate-700 transition hover:bg-slate-100 hover:text-slate-900"
                    >
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        {{ __('common.actions.back') }}
                    </a>
                    <div class="flex items-center gap-3">
                        <button
                            type="submit"
                            class="inline-flex items-center gap-2 rounded-lg bg-indigo-600 px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                        >
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            {{ __('common.actions.save') }}
                        </button>
                    </div>
                </div>
            </form>

            <!-- Additional Info -->
            <div class="mt-8 rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
                <div class="flex items-center gap-3">
                    <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-slate-100">
                        <svg class="h-4 w-4 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-sm font-semibold text-slate-900">Role & Permission Notes</h3>
                </div>
                <ul class="mt-3 space-y-2 text-sm text-slate-600">
                    <li class="flex items-start gap-2">
                        <svg class="mt-0.5 h-4 w-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        <span>Roles provide predefined sets of permissions</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <svg class="mt-0.5 h-4 w-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        <span>Direct permissions override role permissions</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <svg class="mt-0.5 h-4 w-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        <span>Changes take effect immediately after saving</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <svg class="mt-0.5 h-4 w-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        <span>Use "Toggle All" buttons to quickly select/deselect all items</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <script>
        function toggleAllRoles() {
            const checkboxes = document.querySelectorAll('.role-checkbox');
            const allChecked = Array.from(checkboxes).every(checkbox => checkbox.checked);
            
            checkboxes.forEach(checkbox => {
                checkbox.checked = !allChecked;
            });
        }

        function toggleAllPermissions() {
            const checkboxes = document.querySelectorAll('.permission-checkbox');
            const allChecked = Array.from(checkboxes).every(checkbox => checkbox.checked);
            
            checkboxes.forEach(checkbox => {
                checkbox.checked = !allChecked;
            });
        }
    </script>
@endsection