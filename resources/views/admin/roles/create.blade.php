@extends('admin.layouts.app')

@section('content')
    <div class="flex items-center justify-between">
        <div>
            <p class="text-xs uppercase tracking-wide text-brand-muted">Roles & Permissions</p>
            <h1 class="text-2xl font-semibold text-brand-ink">Create role</h1>
            <p class="text-sm text-brand-muted">Assign permissions to a new role.</p>
        </div>
        <a href="{{ route('admin.roles.index') }}" class="text-sm font-semibold text-brand-blue">Back to roles</a>
    </div>

    <form method="POST" action="{{ route('admin.roles.store') }}" class="mt-6 space-y-6 rounded-2xl border border-brand-border bg-white p-6 shadow-sm">
        @csrf

        <div>
            <label for="name" class="text-sm font-semibold text-brand-ink">Role name</label>
            <input
                type="text"
                name="name"
                id="name"
                value="{{ old('name') }}"
                class="mt-2 w-full rounded-2xl border border-brand-border bg-brand-input/50 px-4 py-2 text-sm text-brand-ink focus:border-brand-blue focus:outline-none"
                required
            >
            @error('name')
                <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <p class="text-sm font-semibold text-brand-ink">Permissions</p>
            <p class="text-xs text-brand-muted">Choose which permissions this role should have.</p>
            <div class="mt-4 grid gap-4 md:grid-cols-2 lg:grid-cols-3">
                @foreach($permissionGroups as $group => $permissions)
                    <div class="rounded-2xl border border-brand-border bg-brand-input/40 p-4">
                        <p class="text-xs font-semibold uppercase text-brand-muted">{{ $group }}</p>
                        <div class="mt-3 space-y-2">
                            @foreach($permissions as $permission)
                                <label class="flex items-center gap-3 text-sm text-brand-ink">
                                    <input
                                        type="checkbox"
                                        name="permissions[]"
                                        value="{{ $permission->id }}"
                                        class="h-4 w-4 rounded border-brand-border text-brand-blue"
                                        {{ in_array($permission->id, old('permissions', [])) ? 'checked' : '' }}
                                    >
                                    <span class="flex-1">{{ $permission->name }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="flex justify-end">
            <button type="submit" class="inline-flex items-center justify-center rounded-2xl bg-brand-blue px-5 py-2 text-sm font-semibold text-white transition hover:bg-brand-blue/80">
                Save role
            </button>
        </div>
    </form>
@endsection
