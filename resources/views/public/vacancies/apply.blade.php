@extends('layouts.public')

@php
    $breadcrumbItems = [
        ['label' => __('vacancies.public.title'), 'url' => route('vacancies.index')],
        ['label' => $vacancy->title, 'url' => route('vacancies.show', ['slug' => $vacancy->public_slug])],
        ['label' => __('vacancies.public.apply_label'), 'url' => route('vacancies.apply', ['slug' => $vacancy->public_slug])],
    ];

    $prefill = $latestApplication ?? null;
    $prefillApplicant = $applicant ?? null;
    $hasExistingApplication = $hasExistingApplication ?? false;
    $applicationLimitMessage = 'You have already applied for a vacancy. Only one application is allowed.';
    $nationalIdDisplay = $prefillApplicant?->national_id_number ?? $prefill?->national_id_number;
    if ($nationalIdDisplay) {
        $nationalIdDisplay = trim(chunk_split($nationalIdDisplay, 4, ' '));
    }
    $dobDisplay = null;
    if ($prefillApplicant?->date_of_birth || $prefill?->date_of_birth) {
        $dobValue = $prefillApplicant?->date_of_birth ?? $prefill?->date_of_birth;
        $dobDisplay = optional(\Illuminate\Support\Carbon::parse($dobValue))->format('d/m/Y');
    }
@endphp

@section('content')
<div class="min-h-screen bg-gradient-to-b from-gray-50 to-white py-8 px-4 sm:px-6 lg:px-8">
    <div class="max-w-7xl mx-auto">
        <!-- Messages -->
        <div class="space-y-4 mb-8">
            @if(session('error'))
            <div class="rounded-xl bg-gradient-to-r from-rose-50 to-red-50 border border-rose-200 p-5 shadow-sm">
                <div class="flex items-center gap-3">
                    <div class="p-2 bg-rose-100 rounded-lg">
                        <svg class="w-5 h-5 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <p class="text-rose-700 font-medium">{{ session('error') }}</p>
                </div>
            </div>
            @endif

            @if($hasExistingApplication)
            <div class="rounded-xl bg-gradient-to-r from-amber-50 to-orange-50 border border-amber-200 p-5 shadow-sm">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div class="flex items-center gap-3">
                        <div class="p-2 bg-amber-100 rounded-lg">
                            <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m0 0v2m0-2h2m-2 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <p class="text-amber-700 font-medium">{{ $applicationLimitMessage }}</p>
                    </div>
                    <a href="{{ route('applicant.dashboard') }}" 
                       class="inline-flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-amber-500 to-orange-500 text-white font-semibold rounded-lg hover:from-amber-600 hover:to-orange-600 transition-all duration-200">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 012-2h2a2 2 0 012 2v6m-6 0h6" />
                        </svg>
                        {{ __('vacancies.public.dashboard_label') }}
                    </a>
                </div>
            </div>
            @endif
        </div>

        <!-- Header -->
        <div class="mb-10">
            <div class="text-left mb-8">
                <h1 class="text-2xl font-bold text-gray-900 mb-3">{{ __('vacancies.public.apply_title', ['title' => $vacancy->title]) }}</h1>
                <p class="text-lg text-gray-600">{{ __('vacancies.public.apply_subtitle') }}</p>
            </div>

        </div>

        <!-- Application Form -->
        <form method="POST" action="{{ route('vacancies.apply.store', ['slug' => $vacancy->public_slug]) }}" enctype="multipart/form-data" class="space-y-8">
            @csrf

            <!-- Personal Information Card -->
            <div class="bg-white rounded-2xl shadow-lg border border-gray-200 overflow-hidden">
                <div class="p-6 border-b border-gray-100 bg-gradient-to-r from-blue-50 to-indigo-50">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-xl bg-gradient-to-r from-blue-500 to-indigo-500 flex items-center justify-center shadow-sm">
                            <span class="text-lg font-bold text-white">1</span>
                        </div>
                        <div>
                            <h2 class="text-xl font-bold text-gray-900">{{ __('vacancies.public.personal_info') }}</h2>
                            <p class="text-sm text-gray-600">Enter your personal details</p>
                        </div>
                    </div>
                </div>
                <div class="p-6 space-y-6">
                    <!-- Grid: Full Name, Date of Birth, Gender -->
                    <div class="grid md:grid-cols-3 gap-6">
                        <!-- Full Name -->
                        <div>
                            <label for="full_name" class="block text-sm font-semibold text-gray-800 mb-2">
                                {{ __('vacancies.public.full_name') }}
                                <span class="text-rose-500">*</span>
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                </div>
                                <input type="text" id="full_name" name="full_name" value="{{ old('full_name', $prefillApplicant?->full_name ?? $prefill?->full_name) }}"
                                    class="block w-full pl-10 pr-4 py-3 border border-gray-300 rounded-xl bg-white text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 @error('full_name') border-rose-300 ring-2 ring-rose-100 @enderror"
                                   required>
                            </div>
                            @error('full_name')
                                <p class="mt-2 text-sm text-rose-600 flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    {{ $message }}
                                </p>
                            @enderror
                            <p class="mt-2 text-xs text-gray-500">Exactly 3 words, letters only</p>
                        </div>

                        <!-- Date of Birth -->
                        <div>
                            <label for="date_of_birth" class="block text-sm font-semibold text-gray-800 mb-2">
                                {{ __('vacancies.public.date_of_birth') }}
                                <span class="text-rose-500">*</span>
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                </div>
                                <input type="text" id="date_of_birth" name="date_of_birth" value="{{ old('date_of_birth', $dobDisplay) }}"
                                    inputmode="numeric"
                                    class="block w-full pl-10 pr-4 py-3 border border-gray-300 rounded-xl bg-white text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 @error('date_of_birth') border-rose-300 ring-2 ring-rose-100 @enderror"
                                    required>
                            </div>
                            @error('date_of_birth')
                                <p class="mt-2 text-sm text-rose-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-2 text-xs text-gray-500">Format: DD/MM/YYYY</p>
                        </div>

                        <!-- Gender -->
                        <div>
                            <label for="gender" class="block text-sm font-semibold text-gray-800 mb-2">
                                {{ __('vacancies.public.gender') }}
                                <span class="text-rose-500">*</span>
                            </label>
                            <div class="relative">
                                <select id="gender" name="gender"
                                    class="block w-full pl-4 pr-10 py-3 border border-gray-300 rounded-xl bg-white text-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 appearance-none @error('gender') border-rose-300 ring-2 ring-rose-100 @enderror"
                                    required>
                                    <option value="">{{ __('vacancies.public.select') }}</option>
                                    <option value="male" @selected(old('gender', $prefillApplicant?->gender ?? $prefill?->gender) === 'male')>{{ __('vacancies.public.male') }}</option>
                                    <option value="female" @selected(old('gender', $prefillApplicant?->gender ?? $prefill?->gender) === 'female')>{{ __('vacancies.public.female') }}</option>
                                </select>
                                <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                    </svg>
                                </div>
                            </div>
                            @error('gender')
                                <p class="mt-2 text-sm text-rose-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Grid: Nationality, Disability Status -->
                    <div class="grid md:grid-cols-2 gap-6">
                        <!-- Nationality -->
                        <div>
                            <label for="nationality" class="block text-sm font-semibold text-gray-800 mb-2">
                                {{ __('vacancies.public.nationality') }}
                                <span class="text-rose-500">*</span>
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <input type="text" id="nationality" name="nationality" value="{{ old('nationality', $prefillApplicant?->nationality) }}"
                                    class="block w-full pl-10 pr-4 py-3 border border-gray-300 rounded-xl bg-white text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 @error('nationality') border-rose-300 ring-2 ring-rose-100 @enderror"
                                    required>
                            </div>
                            @error('nationality')
                                <p class="mt-2 text-sm text-rose-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-2 text-xs text-gray-500">Letters and spaces only</p>
                        </div>

                        <!-- Disability Status -->
                        <div>
                            <label for="disability_status" class="block text-sm font-semibold text-gray-800 mb-2">
                                {{ __('vacancies.public.disability_status') }}
                                <span class="text-rose-500">*</span>
                            </label>
                            <div class="relative">
                                <select id="disability_status" name="disability_status"
                                    class="block w-full pl-4 pr-10 py-3 border border-gray-300 rounded-xl bg-white text-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 appearance-none @error('disability_status') border-rose-300 ring-2 ring-rose-100 @enderror"
                                    required>
                                    <option value="">{{ __('vacancies.public.select') }}</option>
                                    <option value="0" @selected(old('disability_status', (string) ($prefillApplicant?->disability_status ?? $prefill?->disability_status)) === '0')>{{ __('vacancies.public.no') }}</option>
                                    <option value="1" @selected(old('disability_status', (string) ($prefillApplicant?->disability_status ?? $prefill?->disability_status)) === '1')>{{ __('vacancies.public.yes') }}</option>
                                </select>
                                <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                    </svg>
                                </div>
                            </div>
                            @error('disability_status')
                                <p class="mt-2 text-sm text-rose-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Disability Type -->
                    <div id="disability_type_wrapper" class="{{ old('disability_status', (string) ($prefillApplicant?->disability_status ?? $prefill?->disability_status)) === '1' ? '' : 'hidden' }}">
                        <label for="disability_type" class="block text-sm font-semibold text-gray-800 mb-2">
                            {{ __('vacancies.public.disability_type') }}
                            <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" id="disability_type" name="disability_type" value="{{ old('disability_type', $prefill?->disability_type) }}"
                            class="block w-full px-4 py-3 border border-gray-300 rounded-xl bg-white text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 @error('disability_type') border-rose-300 ring-2 ring-rose-100 @enderror"
                            >
                        @error('disability_type')
                            <p class="mt-2 text-sm text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Education Details Card -->
            <div class="bg-white rounded-2xl shadow-lg border border-gray-200 overflow-hidden">
                <div class="p-6 border-b border-gray-100 bg-gradient-to-r from-emerald-50 to-green-50">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-xl bg-gradient-to-r from-emerald-500 to-green-500 flex items-center justify-center shadow-sm">
                            <span class="text-lg font-bold text-white">2</span>
                        </div>
                        <div>
                            <h2 class="text-xl font-bold text-gray-900">{{ __('vacancies.public.education_details') }}</h2>
                            <p class="text-sm text-gray-600">Provide your education information</p>
                        </div>
                    </div>
                </div>
                <div class="p-6 space-y-6">
                    <!-- Grid: Education Level, Field of Study, University -->
                    <div class="grid md:grid-cols-3 gap-6">
                        <!-- Education Level -->
                        <div>
                            <label for="education_level" class="block text-sm font-semibold text-gray-800 mb-2">
                                {{ __('vacancies.public.education_level') }}
                                <span class="text-rose-500">*</span>
                            </label>
                            <select id="education_level" name="education_level"
                                class="block w-full px-4 py-3 border border-gray-300 rounded-xl bg-white text-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 @error('education_level') border-rose-300 ring-2 ring-rose-100 @enderror"
                                required>
                                <option value="">{{ __('vacancies.public.select') }}</option>
                                <option value="diploma" @selected(old('education_level', $prefillApplicant?->education_level ?? $prefill?->education_level) === 'diploma')>
                                    {{ __('vacancies.public.education_level_diploma') }}
                                </option>
                                <option value="degree" @selected(old('education_level', $prefillApplicant?->education_level ?? $prefill?->education_level) === 'degree')>
                                    {{ __('vacancies.public.education_level_degree') }}
                                </option>
                                <option value="masters" @selected(old('education_level', $prefillApplicant?->education_level ?? $prefill?->education_level) === 'masters')>
                                    {{ __('vacancies.public.education_level_masters') }}
                                </option>
                            </select>
                            @error('education_level')
                                <p class="mt-2 text-sm text-rose-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Field of Study -->
                        <div>
                            <label for="field_of_study" class="block text-sm font-semibold text-gray-800 mb-2">
                                {{ __('vacancies.public.field_of_study') }}
                                <span class="text-rose-500">*</span>
                            </label>
                            <input type="text" id="field_of_study" name="field_of_study" value="{{ old('field_of_study', $prefillApplicant?->field_of_study ?? $prefill?->field_of_study) }}"
                                class="block w-full px-4 py-3 border border-gray-300 rounded-xl bg-white text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 @error('field_of_study') border-rose-300 ring-2 ring-rose-100 @enderror"
                             required>
                            @error('field_of_study')
                                <p class="mt-2 text-sm text-rose-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- University Name -->
                        <div>
                            <label for="university_name" class="block text-sm font-semibold text-gray-800 mb-2">
                                {{ __('vacancies.public.university_name') }}
                                <span class="text-rose-500">*</span>
                            </label>
                            <input type="text" id="university_name" name="university_name" value="{{ old('university_name', $prefillApplicant?->university_name ?? $prefill?->university_name) }}"
                                class="block w-full px-4 py-3 border border-gray-300 rounded-xl bg-white text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 @error('university_name') border-rose-300 ring-2 ring-rose-100 @enderror"
                              required>
                            @error('university_name')
                                <p class="mt-2 text-sm text-rose-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Grid: Graduation Year, GPA, Files -->
                    <div class="grid md:grid-cols-3 gap-6">
                        <!-- Graduation Year -->
                        <div>
                            <label for="graduation_year" class="block text-sm font-semibold text-gray-800 mb-2">
                                {{ __('vacancies.public.graduation_year') }}
                                <span class="text-rose-500">*</span>
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                </div>
                                <input type="number" id="graduation_year" name="graduation_year" value="{{ old('graduation_year', $prefillApplicant?->graduation_year ?? $prefill?->graduation_year) }}"
                                    min="2015" max="2018"
                                    class="block w-full pl-10 pr-4 py-3 border border-gray-300 rounded-xl bg-white text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 @error('graduation_year') border-rose-300 ring-2 ring-rose-100 @enderror"
                                    required>
                            </div>
                            @error('graduation_year')
                                <p class="mt-2 text-sm text-rose-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-2 text-xs text-gray-500"> 2015-2018</p>
                        </div>

                        <!-- GPA -->
                        <div>
                            <label for="gpa" class="block text-sm font-semibold text-gray-800 mb-2">
                                {{ __('vacancies.public.gpa') }}
                                <span class="text-rose-500">*</span>
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                                    </svg>
                                </div>
                                <input type="text" id="gpa" name="gpa" value="{{ old('gpa', $prefillApplicant?->gpa ?? $prefill?->gpa) }}" inputmode="decimal"
                                    pattern="^\d(\.\d{2})?$"
                                    class="block w-full pl-10 pr-4 py-3 border border-gray-300 rounded-xl bg-white text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 @error('gpa') border-rose-300 ring-2 ring-rose-100 @enderror"
                                    required>
                            </div>
                            @error('gpa')
                                <p class="mt-2 text-sm text-rose-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Education Document -->
                        <div>
                            <label for="education_document" class="block text-sm font-semibold text-gray-800 mb-2">
                                {{ __('vacancies.public.education_document') }}
                                @if(! $prefillApplicant)
                                    <span class="text-rose-500">*</span>
                                @endif
                            </label>
                            <input type="file" id="education_document" name="education_document" accept=".pdf"
                                class="block w-full text-sm text-gray-500 file:mr-4 file:py-3 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-semibold file:bg-gradient-to-r file:from-emerald-50 file:to-green-50 file:text-emerald-600 hover:file:from-emerald-100 hover:file:to-green-100 @error('education_document') border-rose-300 ring-2 ring-rose-100 @enderror"
                                @if(! $prefillApplicant) required @endif>
                            @error('education_document')
                                <p class="mt-2 text-sm text-rose-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-2 text-xs text-gray-500">
                                {{ __('vacancies.public.education_document_hint') }}
                                @if($prefillApplicant)
                                    <span class="text-gray-400">Leave empty to reuse your existing document.</span>
                                @endif
                            </p>
                        </div>
                    </div>

                    <!-- Profile Photo -->
                    <div class="md:w-1/2">
                        <label for="profile_photo" class="block text-sm font-semibold text-gray-800 mb-2">
                            {{ __('vacancies.public.profile_photo') }}
                            @if(! $prefillApplicant)
                                <span class="text-rose-500">*</span>
                            @endif
                        </label>
                        <input type="file" id="profile_photo" name="profile_photo" accept=".jpg,.jpeg,.png"
                            class="block w-full text-sm text-gray-500 file:mr-4 file:py-3 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-semibold file:bg-gradient-to-r file:from-blue-50 file:to-indigo-50 file:text-blue-600 hover:file:from-blue-100 hover:file:to-indigo-100 @error('profile_photo') border-rose-300 ring-2 ring-rose-100 @enderror"
                            @if(! $prefillApplicant) required @endif>
                        @error('profile_photo')
                            <p class="mt-2 text-sm text-rose-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-2 text-xs text-gray-500">
                            {{ __('vacancies.public.profile_photo_hint') }}
                            @if($prefillApplicant)
                                <span class="text-gray-400">Leave empty to reuse your existing photo.</span>
                            @endif
                        </p>
                    </div>
                </div>
            </div>

            <!-- Contact Information Card -->
            <div class="bg-white rounded-2xl shadow-lg border border-gray-200 overflow-hidden">
                <div class="p-6 border-b border-gray-100 bg-gradient-to-r from-purple-50 to-pink-50">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-xl bg-gradient-to-r from-purple-500 to-pink-500 flex items-center justify-center shadow-sm">
                            <span class="text-lg font-bold text-white">3</span>
                        </div>
                        <div>
                            <h2 class="text-xl font-bold text-gray-900">{{ __('vacancies.public.contact_info') }}</h2>
                            <p class="text-sm text-gray-600">Provide your contact information</p>
                        </div>
                    </div>
                </div>
                <div class="p-6 space-y-6">
                    <!-- Address -->
                    <div>
                        <label for="address" class="block text-sm font-semibold text-gray-800 mb-2">
                            {{ __('vacancies.public.address') }}
                            <span class="text-rose-500">*</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                            </div>
                            <input type="text" id="address" name="address" value="{{ old('address', $prefillApplicant?->address ?? $prefill?->address) }}"
                                class="block w-full pl-10 pr-4 py-3 border border-gray-300 rounded-xl bg-white text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 @error('address') border-rose-300 ring-2 ring-rose-100 @enderror"
                                required>
                        </div>
                        @error('address')
                            <p class="mt-2 text-sm text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Grid: Phone, National ID -->
                    <div class="grid md:grid-cols-2 gap-6">
                        <!-- Phone -->
                        <div>
                            <label for="phone" class="block text-sm font-semibold text-gray-800 mb-2">
                                {{ __('vacancies.public.phone_number') }}
                                <span class="text-rose-500">*</span>
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                    </svg>
                                </div>
                                <input type="text" id="phone" name="phone" value="{{ old('phone', $prefillApplicant?->phone ?? $prefill?->phone) }}" inputmode="numeric"
                                    maxlength="10"
                                    class="block w-full pl-10 pr-4 py-3 border border-gray-300 rounded-xl bg-white text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 @error('phone') border-rose-300 ring-2 ring-rose-100 @enderror"
                                    required>
                            </div>
                            @error('phone')
                                <p class="mt-2 text-sm text-rose-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-2 text-xs text-gray-500">10 digits only. Example: 0912345678</p>
                        </div>

                        <!-- National ID -->
                        <div>
                            <label for="national_id_number" class="block text-sm font-semibold text-gray-800 mb-2">
                                {{ __('vacancies.public.national_id_number') }}
                                <span class="text-rose-500">*</span>
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2" />
                                    </svg>
                                </div>
                                <input type="text" id="national_id_number" name="national_id_number" value="{{ old('national_id_number', $nationalIdDisplay) }}"
                                    inputmode="numeric" maxlength="19"
                                    class="block w-full pl-10 pr-4 py-3 border border-gray-300 rounded-xl bg-white text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 @error('national_id_number') border-rose-300 ring-2 ring-rose-100 @enderror"
                                    required>
                            </div>
                            @error('national_id_number')
                                <p class="mt-2 text-sm text-rose-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-2 text-xs text-gray-500">16 digits, displayed as 0000 0000 0000 0000</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Login Information Card -->
            <div class="bg-white rounded-2xl shadow-lg border border-gray-200 overflow-hidden">
                <div class="p-6 border-b border-gray-100 bg-gradient-to-r from-orange-50 to-amber-50">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-xl bg-gradient-to-r from-orange-500 to-amber-500 flex items-center justify-center shadow-sm">
                            <span class="text-lg font-bold text-white">4</span>
                        </div>
                        <div>
                            <h2 class="text-xl font-bold text-gray-900">{{ __('vacancies.public.login_info') }}</h2>
                            <p class="text-sm text-gray-600">Create your account credentials</p>
                        </div>
                    </div>
                </div>
                <div class="p-6 space-y-6">
                    <div class="grid md:grid-cols-3 gap-6">
                        <!-- Email -->
                        <div>
                            <label for="email" class="block text-sm font-semibold text-gray-800 mb-2">
                                {{ __('vacancies.public.email') }}
                                <span class="text-rose-500">*</span>
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                    </svg>
                                </div>
                                @if($prefillApplicant)
                                    <input type="email" id="email" name="email" value="{{ old('email', $prefillApplicant->email) }}"
                                        class="block w-full pl-10 pr-4 py-3 border border-gray-300 rounded-xl bg-gray-50 text-gray-600 cursor-not-allowed"
                                        readonly>
                                @else
                                    <input type="email" id="email" name="email" value="{{ old('email') }}"
                                        class="block w-full pl-10 pr-4 py-3 border border-gray-300 rounded-xl bg-white text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 @error('email') border-rose-300 ring-2 ring-rose-100 @enderror"
                                        required>
                                @endif
                            </div>
                            @error('email')
                                <p class="mt-2 text-sm text-rose-600">{{ $message }}</p>
                            @enderror
                        </div>

                        @if(! $prefillApplicant)
                            <!-- Password -->
                            <div>
                                <label for="password" class="block text-sm font-semibold text-gray-800 mb-2">
                                    {{ __('vacancies.public.password') }}
                                    <span class="text-rose-500">*</span>
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
                                        </svg>
                                    </div>
                                    <input type="password" id="password" name="password"
                                        class="block w-full pl-10 pr-4 py-3 border border-gray-300 rounded-xl bg-white text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 @error('password') border-rose-300 ring-2 ring-rose-100 @enderror"
                                        required>
                                </div>
                                @error('password')
                                    <p class="mt-2 text-sm text-rose-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Confirm Password -->
                            <div>
                                <label for="password_confirmation" class="block text-sm font-semibold text-gray-800 mb-2">
                                    {{ __('vacancies.public.confirm_password') }}
                                    <span class="text-rose-500">*</span>
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                        </svg>
                                    </div>
                                    <input type="password" id="password_confirmation" name="password_confirmation"
                                        class="block w-full pl-10 pr-4 py-3 border border-gray-300 rounded-xl bg-white text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                                        required>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="bg-white rounded-2xl shadow-lg border border-gray-200 p-6">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div>
                        <p class="text-sm text-gray-600">
                            <span class="text-rose-500">*</span> Required fields
                        </p>
                        <p class="text-xs text-gray-500 mt-1">All information will be kept confidential</p>
                    </div>
                    
                    <div class="flex items-center gap-4">
                        <a href="{{ route('vacancies.show', ['slug' => $vacancy->public_slug]) }}" 
                           class="px-6 py-3 border border-gray-300 text-gray-700 font-semibold rounded-xl hover:bg-gray-50 transition-all duration-200">
                            {{ __('vacancies.public.cancel') }}
                        </a>
                        
                        @if($hasExistingApplication)
                            <button type="button"
                                class="px-6 py-3 bg-gray-200 text-gray-500 font-semibold rounded-xl cursor-not-allowed"
                                disabled>
                                Application Already Submitted
                            </button>
                        @else
                            <button type="submit"
                                class="px-8 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-semibold rounded-xl shadow-lg hover:from-blue-700 hover:to-indigo-700 hover:shadow-xl transition-all duration-200 transform hover:-translate-y-0.5 active:translate-y-0">
                                <span class="flex items-center gap-2">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                                    </svg>
                                    {{ __('vacancies.public.submit_application') }}
                                </span>
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- JavaScript -->
<script>
    (() => {
        // Disability status toggle
        const statusField = document.getElementById('disability_status');
        const typeWrapper = document.getElementById('disability_type_wrapper');
        const typeField = document.getElementById('disability_type');
        
        if (statusField && typeWrapper && typeField) {
            const toggleField = () => {
                if (statusField.value === '1') {
                    typeWrapper.classList.remove('hidden');
                    typeField.setAttribute('required', 'required');
                } else {
                    typeWrapper.classList.add('hidden');
                    typeField.removeAttribute('required');
                }
            };

            statusField.addEventListener('change', toggleField);
            toggleField();
        }

        // Formatting functions
        const formatNationalId = (value) => {
            const digits = value.replace(/\D/g, '').slice(0, 16);
            return digits.replace(/(\d{4})(?=\d)/g, '$1 ').trim();
        };

        const formatPhone = (value) => value.replace(/\D/g, '').slice(0, 10);

        const formatDob = (value) => {
            const digits = value.replace(/\D/g, '').slice(0, 8);
            if (digits.length <= 2) return digits;
            if (digits.length <= 4) return `${digits.slice(0, 2)}/${digits.slice(2)}`;
            return `${digits.slice(0, 2)}/${digits.slice(2, 4)}/${digits.slice(4)}`;
        };

        // National ID formatting
        const nationalId = document.getElementById('national_id_number');
        if (nationalId) {
            nationalId.addEventListener('input', (event) => {
                event.target.value = formatNationalId(event.target.value);
            });
        }

        // Phone formatting
        const phone = document.getElementById('phone');
        if (phone) {
            phone.addEventListener('input', (event) => {
                event.target.value = formatPhone(event.target.value);
            });
        }

        // Date of Birth formatting
        const dob = document.getElementById('date_of_birth');
        if (dob) {
            dob.addEventListener('input', (event) => {
                event.target.value = formatDob(event.target.value);
            });
        }

        // GPA formatting
        const gpa = document.getElementById('gpa');
        if (gpa) {
            gpa.addEventListener('input', (event) => {
                const cleaned = event.target.value.replace(/[^0-9.]/g, '');
                const parts = cleaned.split('.');
                const normalized = parts.length > 1
                    ? `${parts[0].slice(0, 1)}.${parts.slice(1).join('').slice(0, 2)}`
                    : parts[0].slice(0, 1);
                event.target.value = normalized;
            });
        }

        // Form submit cleanup
        const form = document.querySelector('form[action="{{ route('vacancies.apply.store', ['slug' => $vacancy->public_slug]) }}"]');
        if (form && nationalId) {
            form.addEventListener('submit', () => {
                nationalId.value = nationalId.value.replace(/\s+/g, '');
            });
        }
    })();
</script>
@endsection
