@extends('layouts.public')

@section('content')
    <div class="bg-slate-50 py-16">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-10">
            <div class="grid gap-10 lg:grid-cols-[320px_1fr]">
                <aside class="space-y-6 overflow-y-auto max-h-[70vh] pr-2 lg:pr-0">
                    <div class="rounded-2xl bg-white p-6 shadow-sm">
                        <p class="text-xs font-semibold uppercase tracking-[0.4em] text-blue-600">
                            {{ __('home.services.tagline') }}
                        </p>
                        <h2 class="mt-3 text-xl font-semibold text-gray-900">
                            {{ __('home.services.title') }}
                        </h2>
                        <p class="mt-2 text-sm text-gray-600">
                            {{ __('home.services.description') }}
                        </p>
                    </div>

                    <div class="space-y-4">
                        @forelse($services as $service)
                            <a href="{{ route('services.show', $service->slug) }}"
                               class="flex items-center gap-3 rounded-2xl border border-gray-200 bg-white p-4 text-sm text-gray-700 transition hover:bg-blue-50 hover:border-blue-100"
                            >
                                <span class="flex h-10 w-10 items-center justify-center rounded-2xl bg-gradient-to-br from-blue-500 to-indigo-500 text-white shadow">
                                    <x-heroicon-o-document-text class="h-5 w-5" aria-hidden="true" />
                                </span>
                                <span class="flex-1 font-semibold">
                                    {{ $service->display_title }}
                                </span>
                            </a>
                        @empty
                            <div class="rounded-2xl border border-dashed border-gray-200 bg-white/60 p-4 text-sm text-gray-500">
                                {{ __('common.messages.no_services') }}
                            </div>
                        @endforelse
                    </div>
                </aside>

                <div class="space-y-10">
                    <section class="space-y-4 text-center lg:text-left">
                        <p class="text-sm font-semibold uppercase tracking-[0.3em] text-blue-600">
                            {{ __('public.citizen_charter.overview.label') }}
                        </p>
                        <h1 class="text-3xl font-semibold leading-tight text-slate-900 sm:text-4xl">
                            {{ __('public.citizen_charter.overview.heading') }}
                        </h1>
                        <p class="mx-auto max-w-3xl text-base text-slate-600 lg:mx-0">
                            {{ __('public.citizen_charter.overview.description') }}
                        </p>
                        <p class="text-sm text-slate-500">
                            {{ __('public.citizen_charter.overview.note') }}
                        </p>
                    </section>

                    <section class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
                        @forelse($departments as $department)
                            <a
                                href="{{ route('citizen-charter.department', $department) }}"
                                class="flex flex-col justify-between rounded-2xl border border-slate-200 bg-white p-6 shadow-sm transition hover:border-slate-300 hover:shadow-lg"
                            >
                                <div>
                                    <p class="text-xs uppercase tracking-wide text-slate-500">
                                        {{ __('public.citizen_charter.index.department') }}
                                    </p>
                                    <h2 class="mt-2 text-lg font-semibold text-slate-900">
                                        {{ $department->display_name }}
                                    </h2>
                                </div>
                                <div class="mt-6 flex items-center justify-between text-sm text-slate-500">
                                    <span class="font-semibold text-slate-900">
                                        {{ $department->charter_services_count ?? 0 }} {{ __('public.citizen_charter.index.services_count') }}
                                    </span>
                                    <span class="flex items-center gap-2 font-semibold text-blue-600">
                                        {{ __('public.buttons.view_details') }}
                                        <x-heroicon-o-arrow-top-right-on-square class="h-4 w-4" aria-hidden="true" />
                                    </span>
                                </div>
                            </a>
                        @empty
                            <div class="col-span-full rounded-2xl border border-dashed border-slate-200 bg-white/60 p-8 text-center text-slate-500">
                                {{ __('public.citizen_charter.index.empty') }}
                            </div>
                        @endforelse
                    </section>
                </div>
            </div>
        </div>
    </div>
@endsection
