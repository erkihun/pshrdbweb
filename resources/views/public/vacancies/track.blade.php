@extends('layouts.public')

@php
    $breadcrumbItems = [
        ['label' => __('vacancies.public.title'), 'url' => route('vacancies.index')],
        ['label' => __('vacancies.public.track_title'), 'url' => route('vacancies.track')],
    ];
@endphp

@section('content')
<div class="min-h-screen bg-gradient-to-b from-gray-50 to-white py-8 px-4">
    <div class="max-w-2xl mx-auto">
        <!-- Main Card -->
        <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden">
            <!-- Card Header -->
            <div class="border-b border-gray-200 p-6">
                <div class="space-y-1">
                    <h1 class="text-lg font-bold text-gray-900">{{ __('vacancies.public.track_title') }}</h1>
                    <p class="text-sm text-gray-600">{{ __('vacancies.public.track_subtitle') }}</p>
                </div>
            </div>

            <!-- Card Body -->
            <div class="p-6 space-y-6">
                <!-- Track Form -->
                <form method="POST" action="{{ route('vacancies.track.submit') }}" class="space-y-4">
                    @csrf
                    
                    <div class="space-y-2">
                        <label for="reference_code" class="text-xs font-semibold text-gray-700 uppercase tracking-wide">
                            {{ __('vacancies.public.reference_code') }}
                        </label>
                        <div class="relative">
                            <input
                                type="text"
                                id="reference_code"
                                name="reference_code"
                                value="{{ old('reference_code', $referenceCode ?? '') }}"
                                class="w-full px-4 py-3 text-sm border border-gray-300 rounded-lg bg-white text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors"
                                placeholder="Enter your reference code"
                                required
                            >
                            @error('reference_code')
                                <div class="mt-1 flex items-center gap-1 text-xs text-red-600">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>

                    <button
                        type="submit"
                        class="w-full px-4 py-3 bg-blue-600 text-white text-sm font-semibold rounded-lg hover:bg-blue-700 transition-colors flex items-center justify-center gap-2"
                    >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                        {{ __('vacancies.public.check_status') }}
                    </button>
                </form>

                <!-- Application Status Display -->
                @if(isset($application))
                <div class="space-y-4 p-4 bg-gray-50 rounded-lg border border-gray-200">
                    <!-- Reference Code -->
                    <div class="space-y-1">
                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide">
                            {{ __('vacancies.public.reference_code') }}
                        </p>
                        <p class="text-base font-mono font-semibold text-gray-900">{{ $application->reference_code }}</p>
                    </div>

                    <!-- Application Details -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="space-y-1">
                            <p class="text-xs font-semibold text-gray-700">{{ __('vacancies.public.vacancy') }}</p>
                            <p class="text-sm text-gray-900">{{ $application->vacancy->title }}</p>
                        </div>
                        <div class="space-y-1">
                            <p class="text-xs font-semibold text-gray-700">{{ __('vacancies.public.last_updated') }}</p>
                            <p class="text-sm text-gray-900">{{ $application->updated_at->toDayDateTimeString() }}</p>
                        </div>
                    </div>

                    <!-- Status Display -->
                    <div class="space-y-1">
                        <p class="text-xs font-semibold text-gray-700">{{ __('vacancies.public.status') }}</p>
                        <div class="inline-flex items-center gap-2 px-3 py-2 bg-blue-50 text-blue-700 text-sm font-semibold rounded-lg">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            {{ $application->status_label }}
                        </div>
                    </div>

                    <!-- Additional Info -->
                    <div class="pt-3 border-t border-gray-200">
                        <div class="space-y-2">
                            <div class="flex items-center gap-2">
                                <svg class="w-3.5 h-3.5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <p class="text-xs text-gray-600">Your application is currently being reviewed</p>
                            </div>
                            <div class="flex items-center gap-2">
                                <svg class="w-3.5 h-3.5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                                <p class="text-xs text-gray-600">You will receive email updates on your application status</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- No Record Found -->
                @elseif(request()->isMethod('post'))
                <div class="p-4 bg-red-50 border border-red-200 rounded-lg">
                    <div class="flex items-start gap-2">
                        <svg class="w-4 h-4 text-red-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <div>
                            <p class="text-sm font-medium text-red-700">{{ __('vacancies.public.no_record') }}</p>
                            <p class="text-xs text-red-600 mt-1">Please check your reference code and try again</p>
                        </div>
                    </div>
                </div>
                @endif

            </div>
        </div>
    </div>
</div>
@endsection
