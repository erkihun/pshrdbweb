@extends('admin.layouts.app')

@section('content')
    <div class="space-y-6">
        <div>
            <p class="text-xs uppercase tracking-wide text-slate-400">{{ __('ui.optional_group') }}</p>
            <h1 class="text-2xl font-semibold text-slate-900">Add contact information</h1>
            <p class="text-sm text-slate-500">Provide the bureau’s official contact details that appear on the public contact page.</p>
        </div>

        @php $contactInfo = null; @endphp
        <form method="POST" action="{{ route('admin.contact-info.store') }}" class="space-y-6">
            @csrf
            @include('admin.contact-info._form')
        </form>
    </div>
@endsection
