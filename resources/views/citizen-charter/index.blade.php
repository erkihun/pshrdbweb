@extends('layouts.public')

@section('content')
    @php
        $seoMeta = [
            'title' => __('public.citizen_charter.overview.label'),
            'description' => __('public.citizen_charter.overview.description'),
            'url' => route('citizen-charter.index'),
            'canonical' => route('citizen-charter.index'),
        ];
    @endphp
    <div class="bg-slate-50 py-16">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-10">
            <div class="space-y-10">
                <section class="space-y-4 text-center lg:text-left">
                    <h1 class="text-3xl font-semibold leading-tight text-slate-900 sm:text-4xl">
                        {{ __('public.citizen_charter.overview.heading') }}
                    </h1>
                </section>

                <section class="space-y-6 rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                    <div class="flex flex-wrap items-center justify-between gap-4">
                        <div>
                            <p class="text-sm uppercase  text-blue-500">{{ __('public.citizen_charter.overview.label') }}</p>
                            <p class="mt-2 max-w-2xl text-sm text-slate-500">
                                {{ __('public.citizen_charter.overview.description') }}
                            </p>
                        </div>
                        <span class="inline-flex items-center gap-2 rounded-full border border-slate-200 bg-slate-50 px-4 py-2 text-xs font-semibold uppercase tracking-wide text-slate-600">
                            <x-heroicon-o-building-office-2 class="h-4 w-4 text-blue-600" />
                            {{ __('public.citizen_charter.index.departments_count', ['count' => $departments->count()]) }}
                        </span>
                    </div>

                    <div class="overflow-x-auto rounded-2xl border border-slate-100">
                        <table class="min-w-full divide-y divide-slate-200">
                            <thead class="bg-slate-100">
                                <tr class="text-left text-xs font-semibold uppercase  text-slate-500">
                                    <th class="px-6 py-3">{{ __('public.citizen_charter.index.department') }}</th>
                                    <th class="px-6 py-3">{{ __('public.citizen_charter.index.services') }}</th>
                                    <th class="px-6 py-3">{{ __('public.citizen_charter.index.last_updated') }}</th>
                                    <th class="px-6 py-3 text-right">{{ __('public.buttons.view_details') }}</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 bg-white">
                                @forelse($departments as $department)
                                    <tr class="hover:bg-slate-50">
                                        <td class="px-6 py-4">
                                            <a
                                                href="{{ route('citizen-charter.department', $department) }}"
                                                class="text-base font-semibold text-slate-900 hover:text-blue-600"
                                            >
                                                {{ $department->display_name }}
                                            </a>
                                            <p class="text-sm text-slate-500">
                                                {{ $department->summary ?? __('public.citizen_charter.index.department_summary') }}
                                            </p>
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="inline-flex items-center gap-2 rounded-full bg-blue-50 px-3 py-1 text-xs font-semibold text-blue-600">
                                                <x-heroicon-o-sparkles class="h-3 w-3" />
                                                {{ $department->charter_services_count ?? 0 }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-sm text-slate-500">
                                            {{ $department->updated_at?->translatedFormat('M d, Y') ?? __('public.citizen_charter.index.updated_soon') }}
                                        </td>
                                        <td class="px-6 py-4 text-right">
                                            <a
                                                href="{{ route('citizen-charter.department', $department) }}"
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
                                            {{ __('public.citizen_charter.index.empty') }}
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </section>
            </div>
        </div>
    </div>
@endsection
