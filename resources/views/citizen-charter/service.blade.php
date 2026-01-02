@extends('layouts.public')

@section('content')
    @php
        $seoMeta = [
            'title' => $service->display_name . ' | ' . $department->display_name,
            'description' => strip_tags($service->localized('other_info') ?? $service->localized('prerequisites') ?? 'Learn how to access this service.'),
            'url' => route('citizen-charter.service', [$department, $service]),
            'canonical' => route('citizen-charter.service', [$department, $service]),
            'type' => 'article',
        ];
    @endphp
    <div class="bg-slate-50 py-16">
        <div class="mx-auto max-w-5xl space-y-10 px-4 sm:px-6 lg:px-10">
                <div class="space-y-3">
                    <a href="{{ route('citizen-charter.department', $department) }}" class="text-xs font-semibold uppercase tracking-[0.3em] text-blue-600">
                        {{ $department->display_name }}
                    </a>
                    <h1 class="text-3xl font-semibold text-slate-900">{{ $service->display_name }}</h1>
                    
                </div>

            <div class="grid gap-6 lg:grid-cols-3">
                <div class="lg:col-span-2 space-y-4">
                    @if($service->prerequisites_am || $service->prerequisites_en)
                        <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                            <h2 class="text-lg font-semibold text-slate-900">{{ __('public.citizen_charter.service.prerequisites') }}</h2>
                            <div class="prose prose-slate mt-4 max-w-none">
                                {!! $service->localized('prerequisites') !!}
                            </div>
                        </section>
                    @endif

                    @if($service->other_info_am || $service->other_info_en)
                        <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                            <h2 class="text-lg font-semibold text-slate-900">{{ __('public.citizen_charter.service.other_info') }}</h2>
                            <div class="prose prose-slate mt-4 max-w-none">
                                {!! $service->localized('other_info') !!}
                            </div>
                        </section>
                    @endif
                </div>

                <aside class="space-y-4">
                    <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm space-y-3">
                        <h3 class="text-xs uppercase tracking-[0.3em] text-slate-500">{{ __('public.citizen_charter.service.info_heading') }}</h3>
                        @if($service->delivery_mode_label)
                            <p class="text-sm font-semibold text-slate-900">
                                {{ __('public.citizen_charter.service.delivery_mode') }}:
                                <span class="text-slate-600">{{ $service->delivery_mode_label }}</span>
                            </p>
                        @endif
                        @if($service->fee_amount)
                            <p class="text-sm font-semibold text-slate-900">
                                {{ __('public.citizen_charter.service.fee') }}:
                                <span class="text-slate-600">{{ number_format($service->fee_amount, 2) }} {{ $service->fee_currency ?? 'ETB' }}</span>
                            </p>
                        @else
                            <p class="text-sm font-semibold text-slate-900">
                                {{ __('public.citizen_charter.service.fee') }}:
                                <span class="text-slate-600">{{ __('public.citizen_charter.service.free') }}</span>
                            </p>
                        @endif
                        @if($service->workingDaysLabel)
                            <p class="text-sm font-semibold text-slate-900">
                                {{ __('public.citizen_charter.service.open_days') }}:
                                <span class="text-slate-600">{{ $service->workingDaysLabel }}</span>
                            </p>
                        @endif
                        @if($service->workingHoursLabel)
                            <p class="text-sm font-semibold text-slate-900">
                                {{ __('public.citizen_charter.service.open_hours') }}:
                                <span class="text-slate-600">{{ $service->workingHoursLabel }}</span>
                            </p>
                        @endif
                    </section>

                    @if($department->hasLocationDetails())
                        <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm space-y-3">
                            <h3 class="text-xs uppercase tracking-[0.3em] text-slate-500">
                                {{ __('public.citizen_charter.service.location_heading') }}
                            </h3>
                            <div class="space-y-2 text-sm text-slate-700">
                                @if($department->building_name)
                                    <p><span class="font-semibold text-slate-900">{{ __('public.citizen_charter.service.location_building') }}:</span> {{ $department->building_name }}</p>
                                @endif
                                @if($department->floor_number)
                                    <p><span class="font-semibold text-slate-900">{{ __('public.citizen_charter.service.location_floor') }}:</span> {{ $department->floor_number }}</p>
                                @endif
                                @if($department->side_label)
                                    <p><span class="font-semibold text-slate-900">{{ __('public.citizen_charter.service.location_side') }}:</span> {{ $department->side_label }}</p>
                                @endif
                                @if($department->office_room)
                                    <p><span class="font-semibold text-slate-900">{{ __('public.citizen_charter.service.location_office_room') }}:</span> {{ $department->office_room }}</p>
                                @endif
                            </div>
                            @if($department->localizedAddressNote())
                                <div class="prose prose-slate mt-4 max-w-none">
                                    <p class="text-sm font-semibold text-slate-900">{{ __('public.citizen_charter.service.location_note') }}</p>
                                    {!! $department->localizedAddressNote() !!}
                                </div>
                            @endif
                        </section>
                    @endif
                </aside>
            </div>
        </div>
    </div>
@endsection
