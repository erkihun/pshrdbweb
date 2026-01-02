@extends('layouts.public')

@section('content')
    <div class="bg-slate-50 py-16">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-10">
            <div class="space-y-10">
                    <section class="space-y-4 text-center lg:text-left">
                    
                        <h1 class="text-3xl font-semibold leading-tight text-slate-900 sm:text-4xl">
                            {{ __('public.citizen_charter.overview.heading') }}
                        </h1>
                       
                    </section>

                    <section class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
                        @forelse($departments as $department)
                            <a
                                href="{{ route('citizen-charter.department', $department) }}"
                                class="flex flex-col justify-between rounded-2xl border border-slate-200 bg-white p-6 shadow-sm transition hover:border-slate-300 hover:shadow-lg"
                            >
                                <div>
                                    <span class="inline-flex h-10 w-10 items-center justify-center rounded-xl bg-blue-50 text-blue-600">
                                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 21h18M4 21V4a1 1 0 011-1h14a1 1 0 011 1v17M9 21V7h6v14M9 3h6M7 7h2M15 7h2M7 11h2M15 11h2" />
                                        </svg>
                                    </span>
                                    <h2 class="mt-4 text-lg font-semibold text-slate-900">
                                        {{ $department->display_name }}
                                    </h2>
                                    <p class="text-xs mt-2 uppercase tracking-wide text-slate-500">
                                        {{ __('public.citizen_charter.index.department') }}
                                    </p>
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
