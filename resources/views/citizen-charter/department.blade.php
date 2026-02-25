@extends('layouts.public')

@section('content')
    @php
        $seoMeta = [
            'title' => $department->display_name . ' | ' . __('public.citizen_charter.overview.label'),
            'description' => __('public.citizen_charter.department.description'),
            'url' => route('citizen-charter.department', $department),
            'canonical' => route('citizen-charter.department', $department),
        ];
    @endphp

    <div class="bg-white py-16">
        <div class="mx-auto max-w-7xl space-y-10 px-4 sm:px-6 lg:px-10">
            <div class="flex flex-wrap items-center justify-between gap-4">
                <div>
                    <p class="text-xs uppercase  text-blue-600">
                        {{ __('public.citizen_charter.department.label') }}
                    </p>
                    <h1 class="mt-2 text-3xl font-semibold text-slate-900">
                        {{ $department->display_name }}
                    </h1>
                </div>
                <div class="flex items-center gap-3">
                    <a
                        href="{{ route('citizen-charter.index') }}"
                        class="rounded-lg border border-slate-200 px-4 py-2 text-sm font-semibold text-slate-700 transition hover:border-slate-400"
                    >
                        {{ __('public.buttons.back') }}
                    </a>
                    <span class="text-sm text-slate-500">
                        {{ $services->count() }} {{ __('public.citizen_charter.department.services_label') }}
                    </span>
                </div>
            </div>

            <div class="overflow-x-auto rounded-3xl border border-slate-200 bg-white shadow-sm">
                <table class="min-w-full divide-y divide-slate-200">
                    <thead class="bg-slate-50">
                        <tr class="text-left text-xs font-semibold uppercase  text-slate-500">
                            <th class="px-6 py-3">{{ __('public.citizen_charter.department.services_label') }}</th>
                            <th class="px-6 py-3">{{ __('public.citizen_charter.department.subtitle') ?? __('public.buttons.details') }}</th>
                            <th class="px-6 py-3">{{ __('public.citizen_charter.department.delivery_mode_label') }}</th>
                            <th class="px-6 py-3 text-right">{{ __('public.buttons.view_details') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 bg-white">
                        @forelse($services as $service)
                            <tr class="hover:bg-slate-50">
                                <td class="px-6 py-4 align-top">
                                    <a
                                        href="{{ route('citizen-charter.service', [$department, $service]) }}"
                                        class="text-base font-semibold text-slate-900 hover:text-blue-600"
                                    >
                                        {{ $service->display_name }}
                                    </a>
                                  
                                </td>
                                <td class="px-6 py-4">
                                    <p class="text-sm font-semibold text-slate-900">{{ $service->workingDaysLabel }}</p>
                                    <p class="text-xs text-slate-500">{{ $service->workingHoursLabel }}</p>
                                </td>
                                <td class="px-6 py-4 text-sm text-slate-600">
                                    {{ $service->delivery_mode_label }}
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <a
                                        href="{{ route('citizen-charter.service', [$department, $service]) }}"
                                        class="inline-flex items-center gap-2 rounded-full border border-blue-100 bg-white px-4 py-1 text-sm font-semibold text-blue-600 transition hover:border-blue-300 hover:bg-blue-50"
                                    >
                                        {{ __('public.buttons.view_details') }}
                                        <x-heroicon-o-arrow-top-right-on-square class="h-4 w-4" aria-hidden="true" />
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-12 text-center text-sm text-slate-500">
                                    {{ __('public.citizen_charter.department.empty') }}
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
