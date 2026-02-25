@extends('admin.layouts.app')

@section('content')
    <div class="flex flex-col gap-6">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-semibold text-slate-900">{{ __('common.nav.appointments') }}</h1>
                <p class="text-sm text-slate-500">{{ __('common.labels.appointment_services') }}</p>
            </div>
            <a
                href="{{ route('admin.appointment-services.create') }}"
                class="inline-flex items-center rounded-lg bg-slate-900 px-4 py-2 text-sm font-semibold text-white transition hover:bg-slate-800"
            >
                {{ __('common.actions.create') }}
            </a>
        </div>

        <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
            <table class="w-full text-left text-sm">
                <thead class="bg-slate-50 text-xs uppercase tracking-wide text-slate-500">
                    <tr>
                        <th class="px-6 py-3">{{ __('common.labels.title') }}</th>
                        <th class="px-6 py-3">{{ __('common.labels.appointment_service_duration') }}</th>
                        <th class="px-6 py-3">{{ __('common.labels.appointment_slots') }}</th>
                        <th class="px-6 py-3">{{ __('common.labels.status') }}</th>
                        <th class="px-6 py-3 text-right">{{ __('common.actions.actions') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($services as $service)
                        <tr>
                            <td class="px-6 py-4 font-medium text-slate-900">{{ $service->display_name }}</td>
                            <td class="px-6 py-4 text-slate-500">{{ $service->duration_minutes }} {{ __('common.labels.appointment_service_duration') }}</td>
                            <td class="px-6 py-4 text-slate-500">{{ $service->slots_count }}</td>
                            <td class="px-6 py-4">
                                <span class="{{ $service->is_active ? 'text-emerald-600' : 'text-slate-500' }}">
                                    {{ $service->is_active ? __('common.status.active') : __('common.status.inactive') }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="inline-flex items-center gap-3">
                                    <a
                                        href="{{ route('admin.appointment-services.show', $service) }}"
                                        class="text-sm font-medium text-slate-600 hover:text-slate-900"
                                    >
                                        {{ __('common.actions.view') }}
                                    </a>
                                    <a
                                        href="{{ route('admin.appointment-services.edit', $service) }}"
                                        class="text-sm font-medium text-slate-600 hover:text-slate-900"
                                    >
                                        {{ __('common.actions.edit') }}
                                    </a>
                                    <form method="POST" action="{{ route('admin.appointment-services.destroy', $service) }}">
                                        @csrf
                                        @method('DELETE')
                                        <button
                                            type="submit"
                                            class="text-sm font-medium text-rose-600 hover:text-rose-700"
                                            onclick="return confirm('Are you sure you want to delete this service?')"
                                        >
                                            {{ __('common.actions.delete') }}
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-10 text-center text-slate-500">
                                {{ __('common.messages.no_appointments') }}
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div>
            {{ $services->links() }}
        </div>
    </div>
@endsection

