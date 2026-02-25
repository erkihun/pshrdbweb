@extends('layouts.public')

@section('content')
    @php
        $seoMeta = [
            'title' => $service->display_name . ' | ' . $department->display_name,
            'description' => strip_tags(
                $service->localized('other_info')
                ?? $service->localized('prerequisites')
                ?? 'Learn how to access this service.'
            ),
            'url' => route('citizen-charter.service', [$department, $service]),
            'canonical' => route('citizen-charter.service', [$department, $service]),
            'type' => 'article',
        ];
    @endphp

    <div class="bg-slate-50 py-16">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-10">

            {{-- Header --}}
            <div class="mb-10 space-y-3">
                <a
                    href="{{ route('citizen-charter.department', $department) }}"
                    class="text-xs font-semibold uppercase  text-blue-600"
                >
                    {{ $department->display_name }}
                </a>

                <h1 class="text-3xl font-semibold text-slate-900">
                    {{ $service->display_name }}
                </h1>
            </div>

            {{-- Layout --}}
            <div class="grid gap-8 lg:grid-cols-[280px_1fr]">

                {{-- ASIDE (sticky + scrollable) --}}
                <aside
                    class="sticky top-24 rounded-2xl border border-slate-200 bg-white p-4 shadow-sm
                           max-h-[calc(100vh-6rem)] overflow-hidden"
                >
                    <div class="mb-4 flex items-center gap-2 text-xs font-semibold uppercase  text-slate-500">
                        <x-heroicon-o-building-office-2 class="h-4 w-4 text-blue-600" aria-hidden="true" />
                        <span>{{ __('public.citizen_charter.department.services_label') }}</span>
                    </div>

                    {{-- Scroll container --}}
                    <div class="max-h-[calc(100vh-10.5rem)] overflow-y-auto pr-2 [-webkit-overflow-scrolling:touch]">
                        <div class="space-y-1">
                            @forelse($departmentServices as $departmentService)
                                <a
                                    href="{{ route('citizen-charter.service', [$department, $departmentService]) }}"
                                    class="group flex items-start gap-3 rounded-xl px-3 py-2 text-sm font-medium transition
                                        {{ $departmentService->id === $service->id
                                            ? 'bg-blue-50 text-blue-700'
                                            : 'text-slate-700 hover:bg-slate-100' }}"
                                    {{ $departmentService->id === $service->id ? 'aria-current=page' : '' }}
                                >
                                    <span
                                        class="mt-1 h-2.5 w-2.5 rounded-full
                                        {{ $departmentService->id === $service->id
                                            ? 'bg-blue-600'
                                            : 'bg-slate-300 group-hover:bg-slate-400' }}"
                                    ></span>

                                    <div class="flex items-center gap-2">
                                        <x-heroicon-o-chevron-right
                                            class="h-4 w-4 text-slate-400 transition group-hover:text-slate-500"
                                            aria-hidden="true"
                                        />
                                        <span class="leading-snug">
                                            {{ $departmentService->display_name }}
                                        </span>
                                    </div>
                                </a>
                            @empty
                                <p class="text-xs text-slate-500">
                                    {{ __('public.citizen_charter.department.empty') }}
                                </p>
                            @endforelse
                        </div>
                    </div>
                </aside>

                {{-- RIGHT CONTENT --}}
                <section class="rounded-2xl border border-slate-200 bg-white p-8 shadow-sm space-y-10">

                    {{-- Service Info --}}
                    <div class="space-y-4">
                        <p class="text-xs font-semibold uppercase  text-slate-500">
                            <span class="inline-flex items-center gap-2">
                                <x-heroicon-o-information-circle class="h-4 w-4 text-slate-400" />
                                {{ __('public.citizen_charter.service.info_heading') }}
                            </span>
                        </p>

                        <div class="grid gap-4 text-sm sm:grid-cols-2">
                            @if($service->delivery_mode_label)
                                <p>
                                    <span class="font-semibold text-slate-900">
                                        {{ __('public.citizen_charter.service.delivery_mode') }}:
                                    </span>
                                    <span class="text-slate-600">
                                        {{ $service->delivery_mode_label }}
                                    </span>
                                </p>
                            @endif

                            <p>
                                <span class="font-semibold text-slate-900">
                                    {{ __('public.citizen_charter.service.fee') }}:
                                </span>
                                <span class="text-slate-600">
                                    {{ $service->fee_amount
                                        ? number_format($service->fee_amount, 2).' '.($service->fee_currency ?? 'ETB')
                                        : __('public.citizen_charter.service.free') }}
                                </span>
                            </p>

                            @if($service->workingDaysLabel)
                                <p>
                                    <span class="font-semibold text-slate-900">
                                        {{ __('public.citizen_charter.service.open_days') }}:
                                    </span>
                                    <span class="text-slate-600">
                                        {{ $service->workingDaysLabel }}
                                    </span>
                                </p>
                            @endif

                            @if($service->workingHoursLabel)
                                <p>
                                    <span class="font-semibold text-slate-900">
                                        {{ __('public.citizen_charter.service.open_hours') }}:
                                    </span>
                                    <span class="text-slate-600">
                                        {{ $service->workingHoursLabel }}
                                    </span>
                                </p>
                            @endif
                        </div>
                    </div>

                    {{-- Location --}}
                    @if($department->hasLocationDetails())
                        <div class="border-t pt-8 space-y-3">
                            <h2 class="text-sm font-semibold text-slate-900">
                                <span class="inline-flex items-center gap-2">
                                    <x-heroicon-o-map class="h-4 w-4 text-indigo-500" />
                                    {{ __('public.citizen_charter.service.location_heading') }}
                                </span>
                            </h2>

                            <div class="grid gap-2 text-sm text-slate-700 sm:grid-cols-2">
                                @if($department->building_name)
                                    <p>
                                        <span class="font-semibold">
                                            {{ __('public.citizen_charter.service.location_building') }}:
                                        </span>
                                        {{ $department->building_name }}
                                    </p>
                                @endif
                                @if($department->floor_number)
                                    <p>
                                        <span class="font-semibold">
                                            {{ __('public.citizen_charter.service.location_floor') }}:
                                        </span>
                                        {{ $department->floor_number }}
                                    </p>
                                @endif
                                @if($department->side_label)
                                    <p>
                                        <span class="font-semibold">
                                            {{ __('public.citizen_charter.service.location_side') }}:
                                        </span>
                                        {{ $department->side_label }}
                                    </p>
                                @endif
                                @if($department->office_room)
                                    <p>
                                        <span class="font-semibold">
                                            {{ __('public.citizen_charter.service.location_office_room') }}:
                                        </span>
                                        {{ $department->office_room }}
                                    </p>
                                @endif
                            </div>

                            @if($department->localizedAddressNote())
                                <div class="prose prose-slate max-w-none">
                                    <p class="text-sm font-semibold text-slate-900">
                                        {{ __('public.citizen_charter.service.location_note') }}
                                    </p>
                                    {!! $department->localizedAddressNote() !!}
                                </div>
                            @endif
                        </div>
                    @endif

                    {{-- Prerequisites --}}
                    @if($service->prerequisites_am || $service->prerequisites_en)
                        <div class="border-t pt-8">
                            <h2 class="text-sm font-semibold text-slate-900">
                                <span class="inline-flex items-center gap-2">
                                    <x-heroicon-o-clipboard-document class="h-4 w-4 text-slate-500" />
                                    {{ __('public.citizen_charter.service.prerequisites') }}
                                </span>
                            </h2>
                            <div class="prose prose-slate mt-3 max-w-none">
                                {!! $service->localized('prerequisites') !!}
                            </div>
                        </div>
                    @endif

                    {{-- Other Info --}}
                    @if($service->other_info_am || $service->other_info_en)
                        <div class="border-t pt-8">
                            <h2 class="text-sm font-semibold text-slate-900">
                                <span class="inline-flex items-center gap-2">
                                    <x-heroicon-o-sparkles class="h-4 w-4 text-amber-500" />
                                    {{ __('public.citizen_charter.service.other_info') }}
                                </span>
                            </h2>
                            <div class="prose prose-slate mt-3 max-w-none">
                                {!! $service->localized('other_info') !!}
                            </div>
                        </div>
                    @endif

                </section>
            </div>
        </div>
    </div>
@endsection
