@extends('layouts.public')

@php
    $educationDocUrl = $application->education_document_path
        ? route('applicant.applications.download', $application)
        : null;
    $nationalIdDisplay = $application->national_id_number;
    if ($nationalIdDisplay) {
        $nationalIdDisplay = trim(chunk_split($nationalIdDisplay, 4, ' '));
    }
    $dobDisplay = optional($application->date_of_birth)->format('d/m/Y');
    $deadline = $application->vacancy?->deadline;
    $isDeadlineLocked = $deadline && now()->gt($deadline);
    $deadlineCountdown = $deadline ? $deadline->diffForHumans() : null;
    $inputState = $isDeadlineLocked ? 'bg-gray-50 text-gray-400 cursor-not-allowed' : 'bg-white text-gray-900';
    $selectState = $isDeadlineLocked ? 'bg-gray-50 text-gray-400 cursor-not-allowed' : 'bg-white text-gray-900';
    $fileState = $isDeadlineLocked ? 'bg-gray-50 text-gray-400 cursor-not-allowed' : 'bg-white text-gray-900';
    $submitState = $isDeadlineLocked ? 'bg-gray-300 text-gray-500 cursor-not-allowed' : 'bg-gradient-to-r from-blue-600 to-indigo-600 text-white hover:from-blue-700 hover:to-indigo-700 shadow-lg hover:shadow-xl';
    $profilePhotoUrl = $application->profile_photo_path ? asset('storage/' . $application->profile_photo_path) : null;
@endphp

@section('content')
<div class="min-h-screen bg-gradient-to-b from-gray-50 to-white py-8 px-4 sm:px-6 lg:px-8">
    <div class="max-w-7xl mx-auto">
        <!-- Header -->
        <div class="mb-10">
            <div class="flex items-center justify-between">
                <div>
                    <div class="flex items-center gap-3 mb-3">
                        <div class="p-2 bg-gradient-to-r from-blue-100 to-indigo-100 rounded-xl">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        </div>
                        <h1 class="text-3xl font-bold text-gray-900">{{ __('vacancies.public.profile_title') }}</h1>
                    </div>
                    <p class="text-gray-600">{{ __('vacancies.public.profile_subtitle') }}</p>
                    
                    @if($deadlineCountdown)
                    <div class="mt-4 inline-flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-amber-50 to-orange-50 rounded-full border border-amber-200">
                        <svg class="w-4 h-4 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span class="text-sm font-medium text-amber-700">Deadline {{ $deadlineCountdown }}</span>
                    </div>
                    @endif
                </div>
                
                <!-- Profile Photo Preview -->
                <div class="relative group">
                    <div class="w-20 h-20 rounded-2xl overflow-hidden ring-4 ring-white shadow-lg">
                        @if($profilePhotoUrl)
                            <img src="{{ $profilePhotoUrl }}" alt="Profile" class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full bg-gradient-to-br from-blue-100 to-indigo-200 flex items-center justify-center">
                                <svg class="w-10 h-10 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                            </div>
                        @endif
                    </div>
                    <div class="absolute inset-0 rounded-2xl bg-gradient-to-t from-blue-600/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
                </div>
            </div>
        </div>

        <!-- Messages -->
        <div class="space-y-4 mb-8">
            @if(session('status'))
            <div class="rounded-xl bg-gradient-to-r from-emerald-50 to-green-50 border border-emerald-200 p-5 shadow-sm">
                <div class="flex items-center gap-3">
                    <div class="p-2 bg-emerald-100 rounded-lg">
                        <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <p class="text-emerald-700 font-medium">{{ session('status') }}</p>
                </div>
            </div>
            @endif

            @if($errors->any())
            <div class="rounded-xl bg-gradient-to-r from-rose-50 to-red-50 border border-rose-200 p-5 shadow-sm">
                <div class="flex items-center gap-3">
                    <div class="p-2 bg-rose-100 rounded-lg">
                        <svg class="w-5 h-5 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-rose-700 font-medium">Please fix the errors below</p>
                        <ul class="mt-1 text-sm text-rose-600">
                            @foreach($errors->all() as $error)
                                <li class="flex items-center gap-1">
                                    <span class="w-1 h-1 bg-rose-400 rounded-full"></span>
                                    {{ $error }}
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
            @endif

            @if($isDeadlineLocked)
            <div class="rounded-xl bg-gradient-to-r from-amber-50 to-orange-50 border border-amber-200 p-5 shadow-sm">
                <div class="flex items-center gap-3">
                    <div class="p-2 bg-amber-100 rounded-lg">
                        <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m0 0v2m0-2h2m-2 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-amber-700 font-medium">Profile Updates Locked</p>
                        <p class="text-sm text-amber-600 mt-1">Profile updates are locked after the vacancy deadline.</p>
                    </div>
                </div>
            </div>
            @endif
        </div>

        <div class="grid lg:grid-cols-3 gap-8">
            <!-- Main Form -->
            <div class="lg:col-span-2 space-y-6">
                <form method="POST" action="{{ route('applicant.profile.update') }}" enctype="multipart/form-data" class="space-y-6">
                    @csrf

                    <!-- Personal Information Card -->
                    <div class="bg-white rounded-2xl shadow-lg border border-gray-200 overflow-hidden">
                        <div class="p-6 border-b border-gray-100 bg-gradient-to-r from-blue-50 to-indigo-50">
                            <div class="flex items-center gap-3">
                                <div class="p-3 bg-gradient-to-r from-blue-500 to-indigo-500 rounded-xl shadow-sm">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                </div>
                                <div>
                                    <h2 class="text-xl font-bold text-gray-900">{{ __('vacancies.public.personal_info') }}</h2>
                                    <p class="text-sm text-gray-600">Update your personal details</p>
                                </div>
                            </div>
                        </div>
                        <div class="p-6 space-y-6">
                            <!-- Full Name -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-800 mb-2" for="full_name">
                                    {{ __('vacancies.public.full_name') }}
                                    <span class="text-rose-500">*</span>
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                        </svg>
                                    </div>
                                    <input type="text" id="full_name" name="full_name" value="{{ old('full_name', $application->full_name) }}"
                                        class="block w-full pl-10 pr-4 py-3 border border-gray-300 rounded-xl bg-white text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 @error('full_name') border-rose-300 ring-2 ring-rose-100 @enderror {{ $inputState }}"
                                        placeholder="John Doe Smith" required @disabled($isDeadlineLocked)>
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

                            <!-- Grid: Date of Birth, Gender, Nationality -->
                            <div class="grid md:grid-cols-3 gap-6">
                                <!-- Date of Birth -->
                                <div>
                                    <label class="block text-sm font-semibold text-gray-800 mb-2" for="date_of_birth">
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
                                            placeholder="DD/MM/YYYY" inputmode="numeric"
                                            class="block w-full pl-10 pr-4 py-3 border border-gray-300 rounded-xl bg-white text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 @error('date_of_birth') border-rose-300 ring-2 ring-rose-100 @enderror {{ $inputState }}"
                                            required @disabled($isDeadlineLocked)>
                                    </div>
                                    @error('date_of_birth')
                                        <p class="mt-2 text-sm text-rose-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Gender -->
                                <div>
                                    <label class="block text-sm font-semibold text-gray-800 mb-2" for="gender">
                                        {{ __('vacancies.public.gender') }}
                                        <span class="text-rose-500">*</span>
                                    </label>
                                    <div class="relative">
                                        <select id="gender" name="gender"
                                            class="block w-full pl-4 pr-10 py-3 border border-gray-300 rounded-xl bg-white text-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 appearance-none @error('gender') border-rose-300 ring-2 ring-rose-100 @enderror {{ $selectState }}"
                                            required @disabled($isDeadlineLocked)>
                                            <option value="">{{ __('vacancies.public.select') }}</option>
                                            <option value="male" @selected(old('gender', $application->gender) === 'male')>{{ __('vacancies.public.male') }}</option>
                                            <option value="female" @selected(old('gender', $application->gender) === 'female')>{{ __('vacancies.public.female') }}</option>
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

                                <!-- Nationality -->
                                <div>
                                    <label class="block text-sm font-semibold text-gray-800 mb-2" for="nationality">
                                        Nationality
                                        <span class="text-rose-500">*</span>
                                    </label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                        </div>
                                        <input type="text" id="nationality" name="nationality" value="{{ old('nationality', $applicant->nationality) }}"
                                            class="block w-full pl-10 pr-4 py-3 border border-gray-300 rounded-xl bg-white text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 @error('nationality') border-rose-300 ring-2 ring-rose-100 @enderror {{ $inputState }}"
                                            placeholder="Ethiopian" required @disabled($isDeadlineLocked)>
                                    </div>
                                    @error('nationality')
                                        <p class="mt-2 text-sm text-rose-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <!-- Disability Status -->
                            <div class="grid md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-semibold text-gray-800 mb-2" for="disability_status">
                                        {{ __('vacancies.public.disability_status') }}
                                        <span class="text-rose-500">*</span>
                                    </label>
                                    <div class="relative">
                                        <select id="disability_status" name="disability_status"
                                            class="block w-full pl-4 pr-10 py-3 border border-gray-300 rounded-xl bg-white text-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 appearance-none @error('disability_status') border-rose-300 ring-2 ring-rose-100 @enderror {{ $selectState }}"
                                            required @disabled($isDeadlineLocked)>
                                            <option value="">{{ __('vacancies.public.select') }}</option>
                                            <option value="0" @selected(old('disability_status', (string) $application->disability_status) === '0')>{{ __('vacancies.public.no') }}</option>
                                            <option value="1" @selected(old('disability_status', (string) $application->disability_status) === '1')>{{ __('vacancies.public.yes') }}</option>
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

                                <div id="disability_type_wrapper" class="{{ old('disability_status', (string) $application->disability_status) === '1' ? '' : 'hidden' }}">
                                    <label class="block text-sm font-semibold text-gray-800 mb-2" for="disability_type">
                                        {{ __('vacancies.public.disability_type') }}
                                        <span class="text-rose-500">*</span>
                                    </label>
                                    <input type="text" id="disability_type" name="disability_type" value="{{ old('disability_type', $application->disability_type) }}"
                                        class="block w-full px-4 py-3 border border-gray-300 rounded-xl bg-white text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 @error('disability_type') border-rose-300 ring-2 ring-rose-100 @enderror {{ $inputState }}"
                                        placeholder="If applicable" @disabled($isDeadlineLocked)>
                                    @error('disability_type')
                                        <p class="mt-2 text-sm text-rose-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <!-- Profile Photo -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-800 mb-2" for="profile_photo">
                                    {{ __('vacancies.public.profile_photo') }}
                                </label>
                                <div class="mt-1 flex items-center gap-4">
                                    <div class="relative flex-1">
                                        <input type="file" id="profile_photo" name="profile_photo" accept=".jpg,.jpeg,.png"
                                            class="block w-full text-sm text-gray-500 file:mr-4 file:py-3 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-semibold file:bg-gradient-to-r file:from-blue-50 file:to-indigo-50 file:text-blue-600 hover:file:from-blue-100 hover:file:to-indigo-100 {{ $fileState }}"
                                            @disabled($isDeadlineLocked)>
                                    </div>
                                    @if($profilePhotoUrl)
                                    <a href="{{ $profilePhotoUrl }}" target="_blank" class="inline-flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-emerald-50 to-green-50 text-emerald-700 rounded-xl border border-emerald-200 hover:from-emerald-100 hover:to-green-100 transition-all duration-200">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                        View Photo
                                    </a>
                                    @endif
                                </div>
                                @error('profile_photo')
                                    <p class="mt-2 text-sm text-rose-600">{{ $message }}</p>
                                @enderror
                                <p class="mt-2 text-xs text-gray-500">{{ __('vacancies.public.profile_photo_hint') }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Education Details Card -->
                    <div class="bg-white rounded-2xl shadow-lg border border-gray-200 overflow-hidden">
                        <div class="p-6 border-b border-gray-100 bg-gradient-to-r from-emerald-50 to-green-50">
                            <div class="flex items-center gap-3">
                                <div class="p-3 bg-gradient-to-r from-emerald-500 to-green-500 rounded-xl shadow-sm">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z" />
                                    </svg>
                                </div>
                                <div>
                                    <h2 class="text-xl font-bold text-gray-900">{{ __('vacancies.public.education_details') }}</h2>
                                    <p class="text-sm text-gray-600">Confirm your education history and upload documents</p>
                                </div>
                            </div>
                        </div>
                        <div class="p-6 space-y-6">
                            <!-- Education Level, Field of Study, University -->
                            <div class="grid md:grid-cols-3 gap-6">
                                <div>
                                    <label class="block text-sm font-semibold text-gray-800 mb-2" for="education_level">
                                        {{ __('vacancies.public.education_level') }}
                                        <span class="text-rose-500">*</span>
                                    </label>
                                    <input type="text" id="education_level" name="education_level" value="{{ old('education_level', $application->education_level) }}"
                                        class="block w-full px-4 py-3 border border-gray-300 rounded-xl bg-white text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 @error('education_level') border-rose-300 ring-2 ring-rose-100 @enderror {{ $inputState }}"
                                        placeholder="Bachelor's Degree" required @disabled($isDeadlineLocked)>
                                    @error('education_level')
                                        <p class="mt-2 text-sm text-rose-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-semibold text-gray-800 mb-2" for="field_of_study">
                                        {{ __('vacancies.public.field_of_study') }}
                                        <span class="text-rose-500">*</span>
                                    </label>
                                    <input type="text" id="field_of_study" name="field_of_study" value="{{ old('field_of_study', $application->field_of_study) }}"
                                        class="block w-full px-4 py-3 border border-gray-300 rounded-xl bg-white text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 @error('field_of_study') border-rose-300 ring-2 ring-rose-100 @enderror {{ $inputState }}"
                                        placeholder="Computer Science" required @disabled($isDeadlineLocked)>
                                    @error('field_of_study')
                                        <p class="mt-2 text-sm text-rose-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-semibold text-gray-800 mb-2" for="university_name">
                                        {{ __('vacancies.public.university_name') }}
                                        <span class="text-rose-500">*</span>
                                    </label>
                                    <input type="text" id="university_name" name="university_name" value="{{ old('university_name', $application->university_name) }}"
                                        class="block w-full px-4 py-3 border border-gray-300 rounded-xl bg-white text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 @error('university_name') border-rose-300 ring-2 ring-rose-100 @enderror {{ $inputState }}"
                                        placeholder="University Name" required @disabled($isDeadlineLocked)>
                                    @error('university_name')
                                        <p class="mt-2 text-sm text-rose-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <!-- Graduation Year and GPA -->
                            <div class="grid md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-semibold text-gray-800 mb-2" for="graduation_year">
                                        {{ __('vacancies.public.graduation_year') }}
                                        <span class="text-rose-500">*</span>
                                    </label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                        </div>
                                        <input type="number" id="graduation_year" name="graduation_year" value="{{ old('graduation_year', $application->graduation_year) }}"
                                            min="2015" max="2018"
                                            class="block w-full pl-10 pr-4 py-3 border border-gray-300 rounded-xl bg-white text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 @error('graduation_year') border-rose-300 ring-2 ring-rose-100 @enderror {{ $inputState }}"
                                            placeholder="2018" required @disabled($isDeadlineLocked)>
                                    </div>
                                    @error('graduation_year')
                                        <p class="mt-2 text-sm text-rose-600">{{ $message }}</p>
                                    @enderror
                                    <p class="mt-2 text-xs text-gray-500">Accepted years: 2015-2018</p>
                                </div>

                                <div>
                                    <label class="block text-sm font-semibold text-gray-800 mb-2" for="gpa">
                                        {{ __('vacancies.public.gpa') }}
                                        <span class="text-rose-500">*</span>
                                    </label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                                            </svg>
                                        </div>
                                        <input type="text" id="gpa" name="gpa" value="{{ old('gpa', $application->gpa) }}" inputmode="decimal"
                                            pattern="^\d(\.\d{2})?$"
                                            class="block w-full pl-10 pr-4 py-3 border border-gray-300 rounded-xl bg-white text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 @error('gpa') border-rose-300 ring-2 ring-rose-100 @enderror {{ $inputState }}"
                                            placeholder="3.75" required @disabled($isDeadlineLocked)>
                                    </div>
                                    @error('gpa')
                                        <p class="mt-2 text-sm text-rose-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <!-- Education Document -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-800 mb-2" for="education_document">
                                    {{ __('vacancies.public.education_document') }}
                                </label>
                                <div class="mt-1 flex items-center gap-4">
                                    <div class="relative flex-1">
                                        <input type="file" id="education_document" name="education_document" accept=".pdf"
                                            class="block w-full text-sm text-gray-500 file:mr-4 file:py-3 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-semibold file:bg-gradient-to-r file:from-emerald-50 file:to-green-50 file:text-emerald-600 hover:file:from-emerald-100 hover:file:to-green-100 {{ $fileState }}"
                                            @disabled($isDeadlineLocked)>
                                    </div>
                                    @if($educationDocUrl)
                                    <a href="{{ $educationDocUrl }}" target="_blank" class="inline-flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-blue-50 to-indigo-50 text-blue-700 rounded-xl border border-blue-200 hover:from-blue-100 hover:to-indigo-100 transition-all duration-200">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                        View Document
                                    </a>
                                    @endif
                                </div>
                                @error('education_document')
                                    <p class="mt-2 text-sm text-rose-600">{{ $message }}</p>
                                @enderror
                                <p class="mt-2 text-xs text-gray-500">{{ __('vacancies.public.education_document_hint') }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Contact Information Card -->
                    <div class="bg-white rounded-2xl shadow-lg border border-gray-200 overflow-hidden">
                        <div class="p-6 border-b border-gray-100 bg-gradient-to-r from-purple-50 to-pink-50">
                            <div class="flex items-center gap-3">
                                <div class="p-3 bg-gradient-to-r from-purple-500 to-pink-500 rounded-xl shadow-sm">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                    </svg>
                                </div>
                                <div>
                                    <h2 class="text-xl font-bold text-gray-900">{{ __('vacancies.public.contact_info') }}</h2>
                                    <p class="text-sm text-gray-600">Keep your contact details current</p>
                                </div>
                            </div>
                        </div>
                        <div class="p-6 space-y-6">
                            <!-- Address -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-800 mb-2" for="address">
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
                                    <input type="text" id="address" name="address" value="{{ old('address', $application->address) }}"
                                        class="block w-full pl-10 pr-4 py-3 border border-gray-300 rounded-xl bg-white text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 @error('address') border-rose-300 ring-2 ring-rose-100 @enderror {{ $inputState }}"
                                        placeholder="Full residential address" required @disabled($isDeadlineLocked)>
                                </div>
                                @error('address')
                                    <p class="mt-2 text-sm text-rose-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Phone and National ID -->
                            <div class="grid md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-semibold text-gray-800 mb-2" for="phone">
                                        {{ __('vacancies.public.phone_number') }}
                                        <span class="text-rose-500">*</span>
                                    </label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                            </svg>
                                        </div>
                                        <input type="text" id="phone" name="phone" value="{{ old('phone', $application->phone) }}" inputmode="numeric"
                                            maxlength="10"
                                            class="block w-full pl-10 pr-4 py-3 border border-gray-300 rounded-xl bg-white text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 @error('phone') border-rose-300 ring-2 ring-rose-100 @enderror {{ $inputState }}"
                                            placeholder="0912345678" required @disabled($isDeadlineLocked)>
                                    </div>
                                    @error('phone')
                                        <p class="mt-2 text-sm text-rose-600">{{ $message }}</p>
                                    @enderror
                                    <p class="mt-2 text-xs text-gray-500">10 digits only. Example: 0912345678</p>
                                </div>

                                <div>
                                    <label class="block text-sm font-semibold text-gray-800 mb-2" for="national_id_number">
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
                                            class="block w-full pl-10 pr-4 py-3 border border-gray-300 rounded-xl bg-white text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 @error('national_id_number') border-rose-300 ring-2 ring-rose-100 @enderror {{ $inputState }}"
                                            placeholder="0000 0000 0000 0000" required @disabled($isDeadlineLocked)>
                                    </div>
                                    @error('national_id_number')
                                        <p class="mt-2 text-sm text-rose-600">{{ $message }}</p>
                                    @enderror
                                    <p class="mt-2 text-xs text-gray-500">16 digits, displayed as 0000 0000 0000 0000</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="flex items-center justify-end pt-6">
                        <button type="submit" @disabled($isDeadlineLocked)
                            class="px-8 py-4 rounded-xl font-semibold shadow-lg transition-all duration-200 transform hover:-translate-y-0.5 active:translate-y-0 {{ $submitState }} @if($isDeadlineLocked) cursor-not-allowed @else hover:shadow-xl @endif">
                            <span class="flex items-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                {{ __('vacancies.public.profile_save') }}
                            </span>
                        </button>
                    </div>
                </form>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Documents Card -->
                <div class="bg-white rounded-2xl shadow-lg border border-gray-200 overflow-hidden">
                    <div class="p-6 border-b border-gray-100 bg-gradient-to-r from-amber-50 to-orange-50">
                        <h2 class="text-xl font-bold text-gray-900">Documents</h2>
                        <p class="text-sm text-gray-600">Your uploaded documents</p>
                    </div>
                    <div class="p-6 space-y-4">
                        <!-- Education Document -->
                        <div class="p-4 rounded-xl border border-gray-200 bg-gradient-to-r from-gray-50 to-white hover:from-blue-50 hover:to-indigo-50 transition-all duration-200 group">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <div class="p-2 bg-gradient-to-r from-blue-100 to-indigo-100 rounded-lg group-hover:from-blue-200 group-hover:to-indigo-200 transition-all duration-200">
                                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="font-semibold text-gray-900">{{ __('vacancies.public.education_document') }}</p>
                                        <p class="text-xs text-gray-500">PDF document</p>
                                    </div>
                                </div>
                                @if($educationDocUrl)
                                <a href="{{ $educationDocUrl }}" class="px-3 py-1.5 bg-gradient-to-r from-blue-50 to-indigo-50 text-blue-700 text-sm font-semibold rounded-lg border border-blue-200 hover:from-blue-100 hover:to-indigo-100 transition-all duration-200">
                                    Download
                                </a>
                                @else
                                <span class="px-3 py-1.5 bg-gradient-to-r from-gray-50 to-gray-100 text-gray-500 text-sm font-semibold rounded-lg border border-gray-200">
                                    Not Uploaded
                                </span>
                                @endif
                            </div>
                        </div>

                        <!-- Profile Photo Preview -->
                        <div class="p-4 rounded-xl border border-gray-200 bg-gradient-to-r from-gray-50 to-white">
                            <p class="font-semibold text-gray-900 mb-3">{{ __('vacancies.public.profile_photo') }}</p>
                            @if($profilePhotoUrl)
                            <div class="relative group">
                                <img src="{{ $profilePhotoUrl }}" alt="Profile Photo" class="w-32 h-32 rounded-xl object-cover mx-auto shadow-md">
                                <div class="absolute inset-0 rounded-xl bg-gradient-to-t from-black/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity flex items-end justify-center pb-3">
                                    <a href="{{ $profilePhotoUrl }}" target="_blank" class="px-3 py-1.5 bg-white/90 text-gray-800 text-sm font-semibold rounded-lg backdrop-blur-sm hover:bg-white transition-all duration-200">
                                        View Full Size
                                    </a>
                                </div>
                            </div>
                            @else
                            <div class="w-32 h-32 rounded-xl bg-gradient-to-br from-gray-100 to-gray-200 flex items-center justify-center mx-auto">
                                <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                            </div>
                            <p class="text-center text-xs text-gray-500 mt-3">No photo uploaded</p>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Account Info Card -->
                <div class="bg-white rounded-2xl shadow-lg border border-gray-200 overflow-hidden">
                    <div class="p-6 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-slate-50">
                        <h2 class="text-xl font-bold text-gray-900">{{ __('vacancies.public.account_info') }}</h2>
                        <p class="text-sm text-gray-600">Your account details</p>
                    </div>
                    <div class="p-6">
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-semibold text-gray-800 mb-2">{{ __('vacancies.public.email') }}</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                        </svg>
                                    </div>
                                    <input type="email" value="{{ $application->email }}"
                                        class="block w-full pl-10 pr-4 py-3 border border-gray-300 rounded-xl bg-gray-50 text-gray-600 cursor-not-allowed"
                                        readonly>
                                </div>
                                <p class="mt-2 text-xs text-gray-500">Contact support to change email</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Change Password Card -->
                <div class="bg-white rounded-2xl shadow-lg border border-gray-200 overflow-hidden">
                    <form method="POST" action="{{ route('applicant.profile.password') }}">
                        @csrf
                        <div class="p-6 border-b border-gray-100 bg-gradient-to-r from-orange-50 to-red-50">
                            <h2 class="text-xl font-bold text-gray-900">Change Password</h2>
                            <p class="text-sm text-gray-600">Update your account password</p>
                        </div>
                        <div class="p-6 space-y-6">
                            <!-- Current Password -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-800 mb-2" for="current_password">
                                    {{ __('vacancies.public.current_password') }}
                                    <span class="text-rose-500">*</span>
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m0 0v2m0-2h2m-2 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    </div>
                                    <input type="password" id="current_password" name="current_password"
                                        class="block w-full pl-10 pr-4 py-3 border border-gray-300 rounded-xl bg-white text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all duration-200 @error('current_password') border-rose-300 ring-2 ring-rose-100 @enderror"
                                        placeholder="Current password" required>
                                </div>
                                @error('current_password')
                                    <p class="mt-2 text-sm text-rose-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- New Password -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-800 mb-2" for="password">
                                    {{ __('vacancies.public.new_password') }}
                                    <span class="text-rose-500">*</span>
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
                                        </svg>
                                    </div>
                                    <input type="password" id="password" name="password"
                                        class="block w-full pl-10 pr-4 py-3 border border-gray-300 rounded-xl bg-white text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all duration-200 @error('password') border-rose-300 ring-2 ring-rose-100 @enderror"
                                        placeholder="New password" required>
                                </div>
                                @error('password')
                                    <p class="mt-2 text-sm text-rose-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Confirm Password -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-800 mb-2" for="password_confirmation">
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
                                        class="block w-full pl-10 pr-4 py-3 border border-gray-300 rounded-xl bg-white text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all duration-200"
                                        placeholder="Confirm new password" required>
                                </div>
                            </div>

                            <!-- Submit Button -->
                            <div class="pt-4">
                                <button type="submit"
                                    class="w-full px-6 py-3 bg-gradient-to-r from-orange-500 to-red-500 text-white font-semibold rounded-xl shadow-lg hover:from-orange-600 hover:to-red-600 hover:shadow-xl transition-all duration-200 transform hover:-translate-y-0.5 active:translate-y-0">
                                    <span class="flex items-center justify-center gap-2">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m0 0v2m0-2h2m-2 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        {{ __('vacancies.public.update_password') }}
                                    </span>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
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
        const form = document.querySelector('form[action="{{ route('applicant.profile.update') }}"]');
        if (form && nationalId) {
            form.addEventListener('submit', () => {
                nationalId.value = nationalId.value.replace(/\s+/g, '');
            });
        }
    })();
</script>
@endsection