@extends('admin.layouts.app')

@section('content')
    <div class="space-y-6">
        <div>
            <p class="text-xs uppercase tracking-wide text-slate-400">{{ __('ui.optional_group') }}</p>
            <h1 class="text-2xl font-semibold text-slate-900">Update contact information</h1>
            <p class="text-sm text-slate-500">Keep the bureau contact data accurate across the public portal.</p>
        </div>

        <form method="POST" action="{{ route('admin.contact-info.update', $contactInfo) }}" class="space-y-6">
            @csrf
            @method('PUT')
            @include('admin.contact-info._form')
        </form>
    </div>
@endsection
