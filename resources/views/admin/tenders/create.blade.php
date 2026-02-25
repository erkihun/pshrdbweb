@extends('admin.layouts.app')

@section('content')
    <div class="space-y-6">
        <div>
            <p class="text-xs uppercase tracking-wide text-slate-400">{{ __('ui.optional_group') }}</p>
            <h1 class="text-2xl font-semibold text-slate-900">{{ __('common.actions.create') ?? 'Create tender' }}</h1>
            <p class="text-sm text-slate-500">Add a new tender notice.</p>
        </div>

        @include('admin.tenders._form', [
            'statuses' => $statuses,
            'action' => route('admin.tenders.store'),
            'method' => 'POST',
        ])
    </div>
@endsection
