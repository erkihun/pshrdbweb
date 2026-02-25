@extends('admin.layouts.app')

@section('content')
    <div class="space-y-6">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-semibold text-slate-900">{{ $service->display_name }}</h1>
                <p class="text-sm text-slate-500">{{ __('common.citizen_charter.admin.show.subtitle') }}</p>
            </div>
            <div class="flex items-center gap-2">
                <a
                    href="{{ route('admin.charter-services.edit', $service) }}"
                    class="rounded-lg border border-slate-200 px-4 py-2 text-sm font-semibold text-slate-700 transition hover:border-slate-400"
                >
                    {{ __('common.actions.edit') }}
                </a>
                <a
                    href="{{ route('admin.charter-services.index') }}"
                    class="rounded-lg border border-slate-200 px-4 py-2 text-sm font-semibold text-slate-700 transition hover:border-slate-400"
                >
                    {{ __('common.actions.back') }}
                </a>
            </div>
        </div>

        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm space-y-6">
            <div class="grid gap-6 md:grid-cols-3">
                <div>
                    <p class="text-xs uppercase tracking-wide text-slate-500">{{ __('common.citizen_charter.admin.show.department') }}</p>
                    <p class="font-semibold text-slate-900">{{ $service->department?->display_name }}</p>
                </div>
                <div>
                    <p class="text-xs uppercase tracking-wide text-slate-500">{{ __('common.citizen_charter.admin.show.status') }}</p>
                    <p class="font-semibold text-slate-900">
                        {{ $service->is_active ? __('common.status.active') : __('common.status.inactive') }}
                    </p>
                </div>
                <div>
                    <p class="text-xs uppercase tracking-wide text-slate-500">{{ __('common.citizen_charter.admin.show.sort_order') }}</p>
                    <p class="font-semibold text-slate-900">{{ $service->sort_order }}</p>
                </div>
            </div>

            <div class="grid gap-6 md:grid-cols-2">
                <div>
                    <p class="text-xs uppercase tracking-wide text-slate-500">{{ __('common.citizen_charter.admin.show.delivery_mode') }}</p>
                    <p class="font-semibold text-slate-900">{{ $service->delivery_mode_label }}</p>
                </div>
                <div>
                    <p class="text-xs uppercase tracking-wide text-slate-500">{{ __('common.citizen_charter.admin.show.open_days') }}</p>
                    <p class="font-semibold text-slate-900">{{ $service->workingDaysLabel }}</p>
                    <p class="text-sm text-slate-500">{{ $service->workingHoursLabel }}</p>
                </div>
            </div>

            @if($service->service_place_am || $service->service_place_en)
                <div>
                    <p class="text-xs uppercase tracking-wide text-slate-500">{{ __('common.citizen_charter.admin.show.service_place') }}</p>
                    @if($service->service_place_am)<p class="text-slate-900">{{ $service->service_place_am }}</p>@endif
                    @if($service->service_place_en)<p class="text-slate-500 text-sm">{{ $service->service_place_en }}</p>@endif
                </div>
            @endif

            @if($service->department && $service->department->hasLocationDetails())
                <div>
                    <p class="text-xs uppercase tracking-wide text-slate-500">
                        {{ __('common.citizen_charter.admin.show.department_address') }}
                    </p>
                    <div class="mt-2 space-y-2 text-sm text-slate-700">
                        @if($service->department->building_name)
                            <p><span class="font-semibold text-slate-900">{{ __('common.citizen_charter.admin.show.building') }}:</span> {{ $service->department->building_name }}</p>
                        @endif
                        @if($service->department->floor_number)
                            <p><span class="font-semibold text-slate-900">{{ __('common.citizen_charter.admin.show.floor') }}:</span> {{ $service->department->floor_number }}</p>
                        @endif
                        @if($service->department->side_label)
                            <p><span class="font-semibold text-slate-900">{{ __('common.citizen_charter.admin.show.side') }}:</span> {{ $service->department->side_label }}</p>
                        @endif
                        @if($service->department->office_room)
                            <p><span class="font-semibold text-slate-900">{{ __('common.citizen_charter.admin.show.office_room') }}:</span> {{ $service->department->office_room }}</p>
                        @endif
                    </div>
                    @if($service->department->localizedAddressNote())
                        <div class="prose prose-slate mt-3 max-w-none">
                            {!! $service->department->localizedAddressNote() !!}
                        </div>
                    @endif
                </div>
            @endif

            <div class="grid gap-6">
                @if($service->prerequisites_am || $service->prerequisites_en)
                    <div>
                        <p class="text-xs uppercase tracking-wide text-slate-500">{{ __('common.citizen_charter.admin.show.prerequisites') }}</p>
                        <div class="prose prose-slate mt-3 max-w-none">
                            {!! $service->localized('prerequisites') !!}
                        </div>
                    </div>
                @endif

                @if($service->other_info_am || $service->other_info_en)
                    <div>
                        <p class="text-xs uppercase tracking-wide text-slate-500">{{ __('common.citizen_charter.admin.show.other_info') }}</p>
                        <div class="prose prose-slate mt-3 max-w-none">
                            {!! $service->localized('other_info') !!}
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
