@extends('admin.layouts.app')

@section('content')
    <div class="space-y-6">
        <div>
            <p class="text-xs uppercase tracking-wide text-slate-400">{{ __('ui.administration_group') }}</p>
            <h1 class="text-2xl font-semibold text-slate-900">{{ __('common.admin_organizations.actions.edit_title', ['name' => $organization->name]) }}</h1>
            <p class="text-sm text-slate-500">{{ __('common.admin_organizations.list.edit_description') }}</p>
        </div>

        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
            <form method="POST" action="{{ route('admin.organizations.update', $organization) }}" class="space-y-6">
                @csrf
                @method('PUT')
                @include('admin.organizations._form')

                <div class="flex justify-end gap-3">
                    <a href="{{ route('admin.organizations.index') }}" class="rounded-lg border border-slate-200 px-4 py-2 text-sm font-semibold text-slate-600 hover:bg-slate-50">{{ __('common.actions.cancel') }}</a>
                    <button type="submit" class="rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-blue-500 transition">{{ __('common.actions.update') }}</button>
                </div>
            </form>
        </div>
    </div>
@endsection
