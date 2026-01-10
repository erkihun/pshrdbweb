@extends('layouts.public')

@php
    $breadcrumbItems = [['label' => __('vacancies.public.title'), 'url' => route('vacancies.index')]];
    $applicantUser = auth('applicant')->user();
    $hasExistingApplication = $applicantUser
        ? \App\Models\VacancyApplication::where('applicant_id', $applicantUser->id)->exists()
        : false;
@endphp

@section('content')
<div class="min-h-screen bg-gradient-to-b from-gray-50 to-white py-8 px-4 sm:px-6 lg:px-8">
    <div class="max-w-7xl mx-auto">
        <!-- Header -->
        <div class="mb-10">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6 mb-8">
                <div class="text-center lg:text-left">
                    <h1 class="text-2xl font-bold text-gray-900 mb-3">{{ __('vacancies.public.title') }}</h1>
                    <p class="text-lg text-gray-600">{{ __('vacancies.public.subtitle') }}</p>
                </div>
                <div class="inline-flex items-center gap-3 px-6 py-3 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-2xl border border-blue-200 shadow-sm">
                    <div class="p-2 bg-gradient-to-r from-blue-500 to-indigo-500 rounded-lg">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-gray-500">{{ __('vacancies.public.total_vacancies') }}</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $totalSlots ?? 0 }}</p>
                    </div>
                </div>
            </div>

            <!-- Stats Bar -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
                <div class="bg-gradient-to-r from-emerald-50 to-green-50 rounded-2xl border border-emerald-200 p-5">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-semibold text-gray-500">{{ __('vacancies.public.open_positions') }}</p>
                            <p class="text-2xl font-bold text-gray-900">
                                {{ $vacancies->where('deadline', '>', now())->count() }}
                            </p>
                        </div>
                        <div class="p-3 bg-emerald-100 rounded-xl">
                            <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="bg-gradient-to-r from-amber-50 to-orange-50 rounded-2xl border border-amber-200 p-5">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-semibold text-gray-500">{{ __('vacancies.public.closing_soon') }}</p>
                            <p class="text-2xl font-bold text-gray-900">
                                {{ $vacancies->filter(function($vacancy) {
                                    return $vacancy->deadline && $vacancy->deadline->diffInDays(now()) <= 7 && !$vacancy->deadline->isPast();
                                })->count() }}
                            </p>
                        </div>
                        <div class="p-3 bg-amber-100 rounded-xl">
                            <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="bg-gradient-to-r from-rose-50 to-red-50 rounded-2xl border border-rose-200 p-5">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-semibold text-gray-500">{{ __('vacancies.public.closed_positions') }}</p>
                            <p class="text-2xl font-bold text-gray-900">
                                {{ $vacancies->where('deadline', '<', now())->count() }}
                            </p>
                        </div>
                        <div class="p-3 bg-rose-100 rounded-xl">
                            <svg class="w-6 h-6 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Vacancy Grid -->
        <div id="vacancy-list" class="grid gap-6 lg:grid-cols-2">
            @forelse($vacancies as $vacancy)
                @php
                    $deadline = $vacancy->deadline;
                    $isExpired = $deadline ? $deadline->isPast() : false;
                    $isClosingSoon = $deadline ? $deadline->diffInDays(now()) <= 7 && !$isExpired : false;
                    $employmentType = $vacancy->employment_type ?? $vacancy->employmentType ?? null;

                    if ($isExpired) {
                        $deadlineLabel = __('vacancies.public.applications_closed');
                        $deadlineClasses = 'bg-gradient-to-r from-rose-500 to-red-500 text-white';
                        $deadlineIcon = 'bg-rose-100';
                   
                    } else {
                        $deadlineLabel = __('vacancies.public.open');
                        $deadlineClasses = 'bg-gradient-to-r from-emerald-500 to-green-500 text-white';
                        $deadlineIcon = 'bg-emerald-100';
                    }
                @endphp

                <article class="group relative bg-white rounded-2xl border border-gray-200 shadow-lg hover:shadow-2xl transition-all duration-300 hover:-translate-y-1 overflow-hidden">
                    <!-- Status Ribbon -->
                    <div class="absolute top-4 right-4 z-10">
                        <span class="inline-flex items-center px-4 py-2 rounded-lg text-xs font-bold {{ $deadlineClasses }} shadow-sm">
                            {{ $deadlineLabel }}
                        </span>
                    </div>

                    <div class="p-6">
                        <!-- Header -->
                        <div class="mb-6">
                            <div class="flex items-center gap-3 mb-4">
                                <div class="p-3 bg-gradient-to-r from-blue-100 to-indigo-100 rounded-xl">
                                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                    </svg>
                                </div>
                                <div class="flex-1">
                                    
                                    <h2 class="text-xl font-bold text-gray-900 group-hover:text-blue-600 transition-colors duration-200">{{ $vacancy->title }}</h2>
                                </div>
                            </div>

                            <!-- Tags -->
                            <div class="flex flex-wrap gap-2">
                                <span class="inline-flex items-center gap-2 px-3 py-1.5 bg-gradient-to-r from-gray-50 to-slate-50 text-gray-700 text-sm font-semibold rounded-lg border border-gray-200">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M20.618 5.984A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                    </svg>
                                    {{ __('vacancies.public.slots') }} {{ $vacancy->slots ?? 0 }} 
                                </span>
                                <span class="inline-flex items-center gap-2 px-3 py-1.5 bg-gradient-to-r from-gray-50 to-slate-50 text-gray-700 text-sm font-semibold rounded-lg border border-gray-200">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6l4 2m6-2a10 10 0 11-20 0 10 10 0 0120 0z" />
                                    </svg>
                                    {{ __('vacancies.public.experience') }}: {{ $vacancy->experience ?? __('common.labels.not_available') }}
                                </span>
                            </div>
                        </div>
 <div class="mb-6 p-4 bg-gradient-to-r from-gray-50 to-slate-50 rounded-xl border border-gray-200">
                        <p class="mb-4 text-md text-gray-600">
                            {{ str($vacancy->description)->stripTags()->limit(140) }}
                        </p>
 </div>
                        <!-- Deadline Info -->
                        <div class="mb-4 p-2  border-gray-200">
                            <div class="flex items-center gap-3">
                                <div class="p-2 {{ $deadlineIcon }} rounded-sm">
                                    @if($isExpired)
                                        <svg class="w-5 h-5 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    @elseif($isClosingSoon)
                                        <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    @else
                                        <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                    @endif
                                </div>
                                <div>
                                    <p class="text-sm font-semibold text-gray-700">{{ __('vacancies.public.deadline') }}</p>
                                    <p class="text-sm text-gray-600">{{ $vacancy->displayDeadlineLabel() }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex flex-wrap items-center justify-between gap-3 pt-4 border-t border-gray-200">
                            <a href="{{ route('vacancies.show', ['slug' => $vacancy->public_slug]) }}" 
                               class="inline-flex items-center gap-2 px-4 py-2.5 bg-gradient-to-r from-gray-50 to-slate-50 text-gray-700 font-semibold rounded-lg border border-gray-200 hover:from-gray-100 hover:to-slate-100 hover:border-gray-300 transition-all duration-200 group">
                                <span>{{ __('vacancies.public.view_details') }}</span>
                                <svg class="w-4 h-4 transform group-hover:translate-x-1 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                </svg>
                            </a>

                            @if($isExpired)
                                <span class="inline-flex items-center gap-2 px-4 py-2.5 bg-gradient-to-r from-gray-100 to-slate-100 text-gray-500 font-semibold rounded-lg border border-gray-300 cursor-not-allowed">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                    {{ __('vacancies.public.applications_closed') }}
                                </span>
                            @elseif($hasExistingApplication)
                                <span class="inline-flex items-center gap-2 px-4 py-2.5 bg-gradient-to-r from-amber-50 to-orange-50 text-amber-700 font-semibold rounded-lg border border-amber-200">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    {{ __('vacancies.public.application_submitted') }}
                                </span>
                            @else
                                <a href="{{ route('vacancies.apply', ['slug' => $vacancy->public_slug]) }}" 
                                   class="inline-flex items-center gap-2 px-5 py-2.5 bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-semibold rounded-lg shadow-lg hover:from-blue-700 hover:to-indigo-700 hover:shadow-xl transition-all duration-200 transform hover:-translate-y-0.5">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                    </svg>
                                    {{ __('vacancies.public.apply_now') }}
                                </a>
                            @endif
                        </div>
                    </div>

                    <!-- Gradient Border Effect -->
                    <div class="absolute bottom-0 left-0 right-0 h-1 bg-gradient-to-r from-blue-500 via-indigo-500 to-purple-500 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                </article>
            @empty
                <!-- Empty State -->
                <div class="lg:col-span-2">
                    <div class="rounded-2xl border-2 border-dashed border-gray-300 bg-gradient-to-br from-gray-50 to-white p-12 text-center">
                        <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-gradient-to-r from-blue-50 to-indigo-50 mb-6">
                            <svg class="w-10 h-10 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <h2 class="text-2xl font-bold text-gray-900 mb-3">{{ __('vacancies.public.title') }}</h2>
                        <p class="text-gray-600 mb-6">{{ __('vacancies.public.empty') }}</p>
                        <a href="{{ route('vacancies.index') }}" 
                           class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-semibold rounded-lg shadow-lg hover:from-blue-700 hover:to-indigo-700 hover:shadow-xl transition-all duration-200">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                            </svg>
                            {{ __('vacancies.public.refresh_page') }}
                        </a>
                    </div>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($vacancies->hasPages())
        <div class="mt-12">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div class="flex items-center gap-3 px-4 py-3 bg-gradient-to-r from-gray-50 to-slate-50 rounded-xl border border-gray-200">
                    <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <p class="text-sm font-semibold text-gray-700">
                        {!! __('vacancies.public.showing_range', [
                            'from' => '<span class="text-blue-600">' . ($vacancies->firstItem() ?? 0) . '</span>',
                            'to' => '<span class="text-blue-600">' . ($vacancies->lastItem() ?? 0) . '</span>',
                            'total' => '<span class="text-blue-600">' . $vacancies->total() . '</span>',
                        ]) !!}
                    </p>
                </div>

                <nav class="flex items-center space-x-2">
                    <!-- Previous Button -->
                    @if($vacancies->onFirstPage())
                        <span class="inline-flex items-center px-3 py-2 rounded-lg bg-gray-100 text-gray-400 cursor-not-allowed">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                            </svg>
                        </span>
                    @else
                        <a href="{{ $vacancies->previousPageUrl() }}" 
                           class="inline-flex items-center px-3 py-2 rounded-lg bg-gradient-to-r from-gray-50 to-slate-50 text-gray-700 border border-gray-200 hover:from-gray-100 hover:to-slate-100 hover:border-gray-300 transition-all duration-200">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                            </svg>
                        </a>
                    @endif

                    <!-- Page Numbers -->
                    @foreach($vacancies->getUrlRange(1, $vacancies->lastPage()) as $page => $url)
                        @if($page == $vacancies->currentPage())
                            <span class="inline-flex items-center justify-center w-10 h-10 rounded-lg bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-semibold shadow-sm">
                                {{ $page }}
                            </span>
                        @else
                            <a href="{{ $url }}" 
                               class="inline-flex items-center justify-center w-10 h-10 rounded-lg bg-gradient-to-r from-gray-50 to-slate-50 text-gray-700 border border-gray-200 hover:from-gray-100 hover:to-slate-100 hover:border-gray-300 transition-all duration-200 font-semibold">
                                {{ $page }}
                            </a>
                        @endif
                    @endforeach

                    <!-- Next Button -->
                    @if($vacancies->hasMorePages())
                        <a href="{{ $vacancies->nextPageUrl() }}" 
                           class="inline-flex items-center px-3 py-2 rounded-lg bg-gradient-to-r from-gray-50 to-slate-50 text-gray-700 border border-gray-200 hover:from-gray-100 hover:to-slate-100 hover:border-gray-300 transition-all duration-200">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </a>
                    @else
                        <span class="inline-flex items-center px-3 py-2 rounded-lg bg-gray-100 text-gray-400 cursor-not-allowed">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </span>
                    @endif
                </nav>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
