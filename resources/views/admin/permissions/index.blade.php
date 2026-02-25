@extends('admin.layouts.app')

@section('content')
    <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
        <div>
            <p class="text-xs uppercase tracking-wide text-brand-muted">Roles & Permissions</p>
            <h1 class="text-2xl font-semibold text-brand-ink">Permissions</h1>
            <p class="text-sm text-brand-muted">Create and manage granular permissions for roles.</p>
        </div>
        <a
            href="{{ route('admin.permissions.create') }}"
            class="inline-flex items-center rounded-2xl border border-brand-border bg-white px-4 py-2 text-sm font-semibold text-brand-ink shadow-sm transition hover:border-brand-blue hover:text-brand-blue"
        >
            Add permission
        </a>
    </div>

    @foreach (['success', 'error'] as $status)
        @if(session($status))
            <div class="mt-4 rounded-2xl border border-{{ $status === 'success' ? 'brand-green' : 'brand-red' }}-200/70 bg-{{ $status === 'success' ? 'green' : 'red' }}-50 p-4 text-sm text-{{ $status === 'success' ? 'brand-green' : 'brand-red' }}">
                {{ session($status) }}
            </div>
        @endif
    @endforeach

    <div class="mt-6 grid gap-4 sm:grid-cols-2">
        @forelse($permissions as $permission)
            <div class="flex items-center justify-between rounded-2xl border border-brand-border bg-white px-4 py-3 shadow-sm">
                <div>
                    <p class="font-semibold text-brand-ink">{{ $permission->name }}</p>
                    <p class="text-xs uppercase tracking-wide text-brand-muted">{{ $permission->guard_name }}</p>
                </div>
                <div class="flex items-center gap-2">
                    <a
                        href="{{ route('admin.permissions.edit', $permission) }}"
                        class="rounded-2xl border border-brand-border bg-white px-3 py-1 text-xs font-semibold text-brand-ink transition hover:border-brand-blue hover:text-brand-blue"
                    >
                        Edit
                    </a>
                    <form method="POST" action="{{ route('admin.permissions.destroy', $permission) }}">
                        @csrf
                        @method('DELETE')
                        <button
                            type="submit"
                            class="rounded-2xl border border-red-200 bg-red-50 px-3 py-1 text-xs font-semibold text-red-600 transition hover:border-red-400 hover:bg-red-100"
                        >
                            Delete
                        </button>
                    </form>
                </div>
            </div>
        @empty
            <div class="col-span-full rounded-2xl border border-dashed border-brand-border bg-brand-input/60 p-6 text-center text-sm text-brand-muted">
                No permissions defined yet.
            </div>
        @endforelse
    </div>
@endsection
