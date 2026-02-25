<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('common.nav.appointments') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto space-y-12 px-4 sm:px-6 lg:px-8">
            <section class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-sky-600 to-indigo-700 p-8 text-white shadow-xl shadow-blue-900/40">
                <div class="flex flex-col gap-6 lg:flex-row lg:items-center">
                    <div class="space-y-3">

                        <h4 class="text-2xl font-bold leading-tight sm:text-2xl">
                            {{ __('common.labels.appointments') }}
                        </h4>
                      
                        <div class="flex flex-wrap gap-3">
                            <a
                                href="{{ route('appointments.track.form') }}"
                                class="inline-flex items-center gap-2 rounded-full border border-white/40 bg-white/20 px-5 py-2 text-xs font-semibold uppercase tracking-wide text-white transition hover:bg-white/40"
                            >
                                {{ __('common.actions.search') }} {{ __('common.labels.reference_code') }}
                            </a>
                            <a
                                href="{{ route('appointments.create') }}"
                                class="inline-flex items-center gap-2 rounded-full bg-white px-5 py-2 text-xs font-semibold uppercase tracking-wide text-slate-900 transition hover:bg-blue-50"
                            >
                                {{ __('common.actions.book') }} {{ __('common.labels.appointment') }}
                            </a>
                        </div>
                    </div>
                  
                </div>
                <div class="pointer-events-none absolute inset-y-0 right-0 hidden w-64 opacity-10 lg:block">
                    <div class="absolute inset-0 rounded-3xl bg-white/30 blur-3xl"></div>
                </div>
            </section>

            @if(session('success'))
                <div class="rounded-2xl border border-emerald-200 bg-emerald-50 p-4 text-sm text-emerald-700">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="rounded-2xl border border-rose-200 bg-rose-50 p-4 text-sm text-rose-700">
                    {{ session('error') }}
                </div>
            @endif

            <section class="space-y-8">
                <div class="flex flex-wrap items-end justify-between gap-4">
                    <div>
                      
                        <h2 class="text-3xl font-bold text-slate-900">
                            {{ __('common.appointments.service_heading') }}
                        </h2>
                      
                    </div>
                    <a
                        href="{{ route('appointments.track.form') }}"
                        class="inline-flex items-center gap-2 rounded-full border border-gray-200 bg-white px-4 py-2 text-xs font-semibold uppercase tracking-wide text-slate-700 transition hover:border-blue-200 hover:text-blue-600"
                    >
                        {{ __('common.labels.track_request') ?? 'Track appointment' }}
                    </a>
                </div>

                <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
                    @forelse($services as $service)
                        <article class="flex flex-col justify-between space-y-4 rounded-3xl border border-gray-100 bg-white p-6 shadow-sm transition hover:-translate-y-1 hover:shadow-xl">
                            <div class="space-y-3">
                                <div class="inline-flex items-center gap-2 rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold uppercase tracking-wide text-slate-500">
                                    <span class="h-1.5 w-1.5 rounded-full bg-blue-500"></span>
                                    {{ $service->duration_minutes }} {{ __('common.labels.appointment_service_duration') }}
                                </div>
                                <h3 class="text-2xl font-semibold text-slate-900">
                                    {{ $service->display_name }}
                                </h3>
                                @if($service->display_description)
                                    <p class="text-sm text-slate-500 leading-relaxed line-clamp-3">
                                        {{ $service->display_description }}
                                    </p>
                                @endif
                            </div>

                            <div class="flex justify-between text-sm text-slate-500">
                                <div>
                                    <p class="text-xs font-medium uppercase tracking-wide text-slate-400">
                                        {{ __('common.labels.appointment_service') }}
                                    </p>
                                   
                                </div>
                                <div class="text-right">
                                    <p class="text-xs font-medium uppercase tracking-wide text-slate-400">
                                        {{ __('common.labels.status') }}
                                    </p>
                                    <p class="text-sm font-semibold text-slate-900">
                                        {{ $service->is_available ? __('common.appointments.status_open') : __('common.appointments.status_full') }}
                                    </p>
                                </div>
                            </div>

                            <div class="flex flex-wrap items-center justify-between gap-3 pt-4">
                                <a
                                    href="{{ route('appointments.create', ['service' => $service->slug]) }}"
                                    class="inline-flex items-center justify-center gap-2 rounded-full bg-blue-600 px-5 py-2 text-xs font-semibold uppercase tracking-wide text-white transition hover:bg-blue-500"
                                >
                                    {{ __('common.actions.book') }}
                                </a>
                                <a
                                    href="{{ route('appointments.track.form') }}"
                                    class="text-xs font-semibold uppercase tracking-wide text-blue-600 transition hover:text-blue-800"
                                >
                                    {{ __('common.labels.track_request') ?? __('common.appointments.track_button') }}
                                </a>
                            </div>
                        </article>
                    @empty
                        <div class="rounded-2xl border border-dashed border-gray-200 bg-white p-8 text-center text-sm text-gray-500">
                            {{ __('common.messages.no_appointments') }}
                        </div>
                    @endforelse
                </div>
            </section>
        </div>
    </div>
</x-app-layout>
