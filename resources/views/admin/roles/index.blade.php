@extends('admin.layouts.app')

@section('content')
    <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
        <div>
            <p class="text-xs uppercase tracking-wide text-brand-muted">Roles & Permissions</p>
            <h1 class="text-2xl font-semibold text-brand-ink">Roles</h1>
            <p class="text-sm text-brand-muted">Manage system roles and their permissions.</p>
        </div>
        <a
            href="{{ route('admin.roles.create') }}"
            class="inline-flex items-center rounded-2xl border border-brand-border bg-white px-4 py-2 text-sm font-semibold text-brand-ink shadow-sm transition hover:border-brand-blue hover:text-brand-blue"
        >
            Create role
        </a>
    </div>

    @foreach (['success', 'error'] as $status)
        @if(session($status))
            <div class="mt-4 rounded-2xl border border-{{ $status === 'success' ? 'brand-green' : 'brand-red' }}-200/70 bg-{{ $status === 'success' ? 'green' : 'red' }}-50 p-4 text-sm text-{{ $status === 'success' ? 'brand-green' : 'brand-red' }}">
                {{ session($status) }}
            </div>
        @endif
    @endforeach

    <div class="mt-6 overflow-hidden rounded-2xl border border-brand-border bg-white shadow-sm">
        <table class="min-w-full divide-y divide-brand-border text-sm">
            <thead class="bg-brand-bg text-left text-xs uppercase tracking-wide text-brand-muted">
                <tr>
                    <th class="px-6 py-4">Role</th>
                    <th class="px-6 py-4">Permissions</th>
                    <th class="px-6 py-4">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-brand-border">
                @foreach($roles as $role)
                    <tr class="hover:bg-brand-bg/50">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2">
                                <span class="font-semibold text-brand-ink">{{ $role->name }}</span>
                                @if($role->name === 'Admin')
                                    <span class="rounded-full bg-yellow-100 px-2 py-1 text-[10px] font-semibold uppercase tracking-wider text-yellow-700">Protected</span>
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-4 text-brand-muted">
                            {{ $role->permissions_count }} permissions
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2">
                                <a
                                    href="{{ route('admin.roles.edit', $role) }}"
                                    class="rounded-2xl border border-brand-border bg-white px-3 py-1 text-xs font-semibold text-brand-ink transition hover:border-brand-blue hover:text-brand-blue"
                                >
                                    Edit
                                </a>
                                @if($role->name !== 'Admin')
                                    <form method="POST" action="{{ route('admin.roles.destroy', $role) }}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="rounded-2xl border border-red-200 bg-red-50 px-3 py-1 text-xs font-semibold text-red-600 transition hover:border-red-400 hover:bg-red-100">
                                            Delete
                                        </button>
                                    </form>
                                @else
                                    <button type="button" disabled class="rounded-2xl border border-yellow-200 bg-yellow-50 px-3 py-1 text-xs font-semibold text-yellow-600">
                                        Delete
                                    </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                @endforeach
                @unless($roles->count())
                    <tr>
                        <td colspan="3" class="px-6 py-6 text-center text-sm text-brand-muted">No roles configured.</td>
                    </tr>
                @endunless
            </tbody>
        </table>
    </div>
@endsection
