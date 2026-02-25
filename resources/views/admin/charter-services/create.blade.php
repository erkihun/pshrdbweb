@extends('admin.layouts.app')

@section('content')
    <div class="space-y-6">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-semibold text-slate-900">{{ __('common.citizen_charter.admin.actions.create_title') }}</h1>
                <p class="text-sm text-slate-500">{{ __('common.citizen_charter.admin.actions.create_description') }}</p>
            </div>
            <a
                href="{{ route('admin.charter-services.index') }}"
                class="inline-flex items-center gap-2 rounded-lg border border-slate-200 px-4 py-2 text-sm font-semibold text-slate-700 transition hover:border-slate-400"
            >
                {{ __('common.actions.back') }}
            </a>
        </div>

        <form method="POST" action="{{ route('admin.charter-services.store') }}">
            @csrf
            <div class="space-y-6 rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                @include('admin.charter-services._form', ['service' => $service, 'departments' => $departments])
                <div class="flex flex-wrap items-center justify-end gap-3">
                    <a
                        href="{{ route('admin.charter-services.index') }}"
                        class="rounded-lg border border-slate-200 px-4 py-2 text-sm font-semibold text-slate-600 transition hover:border-slate-400"
                    >
                        {{ __('common.actions.cancel') }}
                    </a>
                    <button
                        type="submit"
                        class="rounded-lg bg-slate-900 px-5 py-2 text-sm font-semibold text-white transition hover:bg-slate-800"
                    >
                        {{ __('common.actions.save') }}
                    </button>
                </div>
            </div>
        </form>
    </div>
@endsection
