@extends('admin.layouts.app')

@section('content')
    <x-admin.card class="space-y-4">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <p class="text-xs text-brand-muted">Search</p>
                <h2 class="text-lg font-semibold text-brand-ink">Results for &ldquo;{{ $query }}&rdquo;</h2>
            </div>
            <p class="text-xs text-brand-muted">{{ $hasQuery ? 'Showing cached results' : 'No query submitted yet' }}</p>
        </div>
    </x-admin.card>

    @if(! $hasQuery)
        <x-admin.card class="text-center text-sm text-brand-muted">
            Submit a query using the search box above to explore posts, services, documents, and pages.
        </x-admin.card>
    @endif
@endsection
