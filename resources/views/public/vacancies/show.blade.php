@extends('layouts.public')

@php
    $breadcrumbItems = [
        ['label' => __('vacancies.public.title'), 'url' => route('vacancies.index')],
        ['label' => $vacancy->title, 'url' => route('vacancies.show', ['slug' => $vacancy->public_slug])],
    ];

    $deadline = $vacancy->deadline;
    $isExpired = $deadline ? $deadline->isPast() : false;
    $isClosingSoon = $deadline ? $deadline->diffInDays(now()) <= 7 && !$isExpired : false;
    $employmentType = $vacancy->employment_type ?? $vacancy->employmentType ?? null;
    $applicantUser = auth('applicant')->user();
    $hasExistingApplication = $applicantUser
        ? \App\Models\VacancyApplication::where('applicant_id', $applicantUser->id)->exists()
        : false;
    $applicationLimitMessage = 'You have already applied for a vacancy. Only one application is allowed.';
@endphp

@section('content')
<div class="min-h-screen bg-gradient-to-b from-gray-50 to-white py-8 px-4">
    <div class="max-w-6xl mx-auto">
        <!-- Messages -->
        @if(session('error'))
        <div class="mb-6">
            <div class="rounded-lg bg-rose-50 border border-rose-200 p-4">
                <div class="flex items-center gap-2">
                    <svg class="w-4 h-4 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <p class="text-sm text-rose-700">{{ session('error') }}</p>
                </div>
            </div>
        </div>
        @endif

        <!-- Main Card -->
        <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden">
            <!-- Card Header -->
            <div class="border-b border-gray-200 p-6">
                <div class="flex flex-wrap items-center gap-2 mb-4">
                    @if($isExpired)
                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-rose-100 text-rose-700 text-xs font-semibold rounded-lg">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                        {{ __('vacancies.public.applications_closed') }}
                    </span>
                  
                    @else
                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-emerald-100 text-emerald-700 text-xs font-semibold rounded-lg">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        {{ __('vacancies.public.open') }}
                    </span>
                    @endif
                   
                </div>

                <h1 class="text-xl font-bold text-gray-900 mb-2">{{ $vacancy->title }}</h1>
                <p class="text-sm text-gray-600">{{ $vacancy->notes ?? __('vacancies.public.detail_summary', ['title' => $vacancy->title]) }}</p>
            </div>

            <!-- Card Body - Grid Layout -->
            <div class="p-6">
                <div class="grid gap-6">
                    <!-- Key Information -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="p-4 bg-gray-50 rounded-lg border border-gray-200">
                            <div class="flex items-center gap-3">
                                <div class="p-2 bg-blue-100 rounded-lg">
                                    <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-xs font-semibold text-gray-500">{{ __('vacancies.public.location') }}</p>
                                    <p class="text-sm font-medium text-gray-900">{{ $vacancy->location ?? __('common.labels.not_available') }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="p-4 bg-gray-50 rounded-lg border border-gray-200">
                            <div class="flex items-center gap-3">
                                <div class="p-2 bg-emerald-100 rounded-lg">
                                    <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-xs font-semibold text-gray-500">{{ __('vacancies.public.status') }}</p>
                                    <p class="text-sm font-medium text-gray-900">{{ ucfirst($vacancy->status) }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="p-4 bg-gray-50 rounded-lg border border-gray-200">
                            <div class="flex items-center gap-3">
                                <div class="p-2 {{ $isExpired ? 'bg-rose-100' : ($isClosingSoon ? 'bg-amber-100' : 'bg-emerald-100') }} rounded-lg">
                                    @if($isExpired)
                                        <svg class="w-4 h-4 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    @elseif($isClosingSoon)
                                        <svg class="w-4 h-4 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    @else
                                        <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                    @endif
                                </div>
                                <div>
                                    <p class="text-xs font-semibold text-gray-500">{{ __('vacancies.public.deadline') }}</p>
                                    <p class="text-sm font-medium text-gray-900">{{ $vacancy->displayDeadlineLabel() }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Description Section -->
                    <div class="space-y-4">
                        <div class="flex items-center gap-2">
                            <div class="p-2 bg-blue-100 rounded-lg">
                                <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                            </div>
                            <h2 class="text-lg font-semibold text-gray-900">{{ __('vacancies.public.description') }}</h2>
                        </div>
                        
                        <div class="prose prose-lg max-w-none text-gray-700 bg-gray-50 rounded-lg p-4">
                            @if($vacancy->description)
                                {!! nl2br(e($vacancy->description)) !!}
                            @else
                                <div class="text-center py-4">
                                    <p class="text-gray-500 text-lg">{{ __('vacancies.public.no_description') }}</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Requirements Section -->
                    @if($vacancy->notes)
                    <div class="space-y-4">
                        <div class="flex items-center gap-2">
                            <div class="p-2 bg-emerald-100 rounded-lg">
                                <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <h2 class="text-sm font-semibold text-gray-900">{{ __('vacancies.public.requirements') }}</h2>
                        </div>
                        
                        <div class="prose prose-sm max-w-none text-gray-700 bg-gray-50 rounded-lg p-4">
                            {!! nl2br(e($vacancy->notes)) !!}
                        </div>
                    </div>
                    @endif

                    <!-- Action Section -->
                    <div class="p-4 bg-gray-50 rounded-lg border border-gray-200 space-y-4">
                        <!-- Status Message -->
                        <div class="flex items-start gap-3">
                            @if($isExpired)
                                <div class="p-1.5 bg-rose-100 rounded-md">
                                    <svg class="w-4 h-4 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </div>
                            @elseif($hasExistingApplication)
                                <div class="p-1.5 bg-amber-100 rounded-md">
                                    <svg class="w-4 h-4 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m0 0v2m0-2h2m-2 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                            @else
                                <div class="p-1.5 bg-emerald-100 rounded-md">
                                    <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                </div>
                            @endif
                            <div>
                                <p class="text-sm font-medium {{ $isExpired ? 'text-rose-700' : ($hasExistingApplication ? 'text-amber-700' : 'text-emerald-700') }}">
                                    @if($isExpired)
                                        {{ __('vacancies.public.apply_blocked') }}
                                    @elseif($hasExistingApplication)
                                        Application already submitted
                                    @else
                                        {{ __('vacancies.public.apply_subtitle') }}
                                    @endif
                                </p>
                                @if($hasExistingApplication)
                                    <p class="text-xs text-amber-600 mt-1">{{ $applicationLimitMessage }}</p>
                                @endif
                            </div>
                        </div>

                      

                        <!-- Action Buttons -->
                        <div class="pt-2">
                            @if($isExpired)
                                <button
                                    type="button"
                                    class="w-full px-4 py-3 bg-gray-200 text-gray-500 text-sm font-semibold rounded-lg cursor-not-allowed"
                                    disabled
                                >
                                    <span class="flex items-center justify-center gap-2">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                        {{ __('vacancies.public.applications_closed') }}
                                    </span>
                                </button>
                            @elseif($hasExistingApplication)
                                <div class="space-y-3">
                                    <a
                                        href="{{ route('applicant.dashboard') }}"
                                        class="block w-full px-4 py-3 bg-amber-50 text-amber-700 text-sm font-semibold rounded-lg border border-amber-200 hover:bg-amber-100 transition-colors"
                                    >
                                        <span class="flex items-center justify-center gap-2">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 012-2h2a2 2 0 012 2v6m-6 0h6" />
                                            </svg>
                                            {{ __('vacancies.public.dashboard_label') }}
                                        </span>
                                    </a>
                                    <p class="text-xs text-gray-500 text-center">View your submitted application</p>
                                </div>
                            @else
                                <a
                                    href="{{ route('vacancies.apply', ['slug' => $vacancy->public_slug]) }}"
                                    class="block w-full px-4 py-3 bg-blue-600 text-white text-sm font-semibold rounded-lg hover:bg-blue-700 transition-colors"
                                >
                                    <span class="flex items-center justify-center gap-2">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                        </svg>
                                        {{ __('vacancies.public.apply_now') }}
                                    </span>
                                </a>
                            @endif
                        </div>

                    
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
