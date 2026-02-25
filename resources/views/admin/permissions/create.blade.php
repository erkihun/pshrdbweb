@extends('admin.layouts.app')

@section('content')
    <div class="flex items-center justify-between">
        <div>
            <p class="text-xs uppercase tracking-wide text-brand-muted">Roles & Permissions</p>
            <h1 class="text-2xl font-semibold text-brand-ink">Create permission</h1>
            <p class="text-sm text-brand-muted">Define a new permission to assign to roles.</p>
        </div>
        <a href="{{ route('admin.permissions.index') }}" class="text-sm font-semibold text-brand-blue">Back to permissions</a>
    </div>

    <form method="POST" action="{{ route('admin.permissions.store') }}" class="mt-6 space-y-6 rounded-2xl border border-brand-border bg-white p-6 shadow-sm">
        @csrf

        <div>
            <label for="name" class="text-sm font-semibold text-brand-ink">Permission name</label>
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

        <div class="flex justify-end">
            <button type="submit" class="inline-flex items-center justify-center rounded-2xl bg-brand-blue px-5 py-2 text-sm font-semibold text-white transition hover:bg-brand-blue/80">
                Save permission
            </button>
        </div>
    </form>
@endsection
