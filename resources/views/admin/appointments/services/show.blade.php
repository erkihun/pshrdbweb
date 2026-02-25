@extends('admin.layouts.app')

@section('content')
    <div class="space-y-6">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-semibold text-slate-900">{{ $appointmentService->display_name }}</h1>
                <p class="text-sm text-slate-500">{{ $appointmentService->description_en }}</p>
            </div>
            <div class="flex items-center gap-3">
                <a
                    href="{{ route('admin.appointment-services.edit', $appointmentService) }}"
                    class="inline-flex items-center rounded-lg border border-slate-200 px-4 py-2 text-sm font-semibold text-slate-700 transition hover:border-slate-300 hover:bg-slate-50"
                >
                    {{ __('common.actions.edit') }}
                </a>
                <a
                    href="{{ route('admin.appointment-slots.create') }}"
                    class="inline-flex items-center rounded-lg bg-slate-900 px-4 py-2 text-sm font-semibold text-white transition hover:bg-slate-800"
                >
                    {{ __('common.actions.create') }} {{ __('common.labels.appointment_slot') }}
                </a>
            </div>
        </div>

        <div class="grid gap-6 md:grid-cols-2">
            <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
                <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">{{ __('common.labels.status') }}</p>
                <p class="text-sm font-medium text-slate-800">
                    {{ $appointmentService->is_active ? __('common.status.active') : __('common.status.inactive') }}
                </p>
                <p class="mt-1 text-xs text-slate-500">{{ __('common.labels.appointment_service_duration') }}: {{ $appointmentService->duration_minutes }} {{ __('common.labels.appointment_service_duration') }}</p>
                <p class="mt-3 text-sm text-slate-500">{{ __('common.labels.last_updated') }} {{ $appointmentService->updated_at->diffForHumans() }}</p>
            </div>

            <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
                <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">{{ __('common.labels.search_results') }}</p>
                <p class="text-sm text-slate-700">{{ __('common.labels.appointment_slots') }}: {{ $appointmentService->slots->count() }}</p>
                <p class="text-sm text-slate-700">{{ __('common.labels.status') }}: {{ $appointmentService->is_active ? __('common.status.active') : __('common.status.inactive') }}</p>
            </div>
        </div>

        <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
            <div class="flex items-center justify-between">
                <h2 class="text-lg font-semibold text-slate-900">{{ __('common.labels.appointment_slots') }}</h2>
                <a
                    href="{{ route('admin.appointment-slots.index') }}"
                    class="text-sm font-semibold text-slate-600 hover:text-slate-900"
                >
                    {{ __('common.actions.view') }} {{ __('common.labels.appointment_slots') }}
                </a>
            </div>

            <div class="mt-4 grid gap-4">
                @forelse ($slots as $slot)
                    <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-semibold text-slate-800">
                                    {{ $slot->starts_at->format('M d, Y') }} Â· {{ $slot->starts_at->format('g:i A') }} - {{ $slot->ends_at->format('g:i A') }}
                                </p>
                                <p class="text-xs text-slate-500">
                                    {{ __('common.labels.appointment_slots') }} Â· {{ $slot->capacity }} {{ __('common.labels.appointment_slots') }}
                                </p>
                            </div>
                            <span class="text-xs font-semibold text-slate-700">
                                {{ max(0, $slot->capacity - ($slot->booked_count ?? 0)) }} {{ __('common.labels.available') }}
                            </span>
                        </div>
                    </div>
                @empty
                    <p class="text-sm text-slate-500">{{ __('common.messages.no_appointments') }}</p>
                @endforelse
            </div>
        </div>
    </div>
@endsection

