@extends('layouts.public')

@section('content')
    <div class="bg-white py-16">
        <div class="mx-auto max-w-6xl space-y-10 px-4 sm:px-6 lg:px-10">
            <div class="flex flex-wrap items-center justify-between gap-4">
                    <div>
                        <p class="text-xs uppercase tracking-[0.4em] text-blue-600">
                            {{ __('public.citizen_charter.department.label') }}
                        </p>
                        <h1 class="mt-2 text-3xl font-semibold text-slate-900">
                            {{ $department->display_name }}
                        </h1>
                        <p class="mt-2 max-w-2xl text-sm text-slate-600">
                            {{ __('public.citizen_charter.department.description') }}
                        </p>
                    </div>
                    <div class="flex items-center gap-2">
                    <a
                        href="{{ route('citizen-charter.index') }}"
                        class="rounded-lg border border-slate-200 px-4 py-2 text-sm font-semibold text-slate-700 transition hover:border-slate-400"
                    >
                        {{ __('public.buttons.back') }}
                    </a>
                    <span class="text-sm text-slate-500">{{ $services->count() }} {{ __('public.citizen_charter.department.services_label') }}</span>
                </div>
            </div>

            <div class="grid gap-6 md:grid-cols-2">
                @forelse($services as $service)
                    <article class="relative flex flex-col justify-between rounded-2xl border border-slate-200 bg-slate-50 p-6 shadow-sm transition hover:-translate-y-1 hover:shadow-lg">
                        <div class="space-y-3">
                            <p class="text-xs uppercase tracking-[0.3em] text-slate-500">
                                {{ $service->delivery_mode_label }}
                            </p>
                            <h2 class="text-2xl font-semibold text-slate-900">
                                {{ $service->display_name }}
                            </h2>
                            <div class="text-sm text-slate-600">
                                <p class="font-semibold text-slate-900">{{ $service->workingDaysLabel }}</p>
                                <p class="text-xs">{{ $service->workingHoursLabel }}</p>
                            </div>
                        </div>
                        <div class="mt-6 flex items-center justify-between text-sm font-semibold text-blue-600">
                            <span class="flex items-center gap-2">
                                {{ __('public.buttons.view_details') }}
                                <x-heroicon-o-arrow-top-right-on-square class="h-4 w-4" aria-hidden="true" />
                            </span>
                        </div>
                        <a
                            href="{{ route('citizen-charter.service', [$department, $service]) }}"
                            class="absolute inset-0"
                            aria-hidden="true"
                            tabindex="-1"
                        ></a>
                    </article>
                @empty
                    <div class="col-span-full rounded-2xl border border-dashed border-slate-200 bg-slate-50/70 px-6 py-8 text-center text-slate-500">
                        {{ __('public.citizen_charter.department.empty') }}
                    </div>
                @endforelse
            </div>
        </div>
    </div>
@endsection
