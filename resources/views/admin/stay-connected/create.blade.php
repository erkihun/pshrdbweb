@extends('admin.layouts.app')

@section('content')
    <div class="space-y-6">
        <div>
            <p class="text-xs uppercase tracking-wide text-slate-400">{{ __('ui.optional_group') }}</p>
            <h1 class="text-2xl font-semibold text-slate-900">Create stay connected item</h1>
        </div>

        @php($stayConnected = null)
        <form method="POST" action="{{ route('admin.stay-connected.store') }}">
            @csrf
            @include('admin.stay-connected._form')
        </form>
    </div>
@endsection
