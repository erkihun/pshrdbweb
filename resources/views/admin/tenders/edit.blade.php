@extends('admin.layouts.app')

@section('content')
    <div class="space-y-6">
        <div>
            <p class="text-xs uppercase tracking-wide text-slate-400">{{ __('ui.optional_group') }}</p>
            <h1 class="text-2xl font-semibold text-slate-900">Edit tender</h1>
            <p class="text-sm text-slate-500">Update the procurement notice details.</p>
        </div>

        @include('admin.tenders._form', [
            'tender' => $tender,
            'statuses' => $statuses,
            'action' => route('admin.tenders.update', $tender),
            'method' => 'PUT',
        ])
    </div>
@endsection
