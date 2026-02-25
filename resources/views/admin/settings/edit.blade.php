@extends('admin.layouts.app')

@section('content')
    @php
        use Illuminate\Support\Facades\Storage;

        $debug = (bool) config('app.debug');

        $logoPath = $branding['logo_path'] ?? null;
        $faviconPath = $branding['favicon_path'] ?? null;

        // Most reliable public URL (requires public/storage -> storage/app/public link)
        $logoUrl = $logoPath ? asset('storage/' . ltrim($logoPath, '/')) : null;
        $faviconUrl = $faviconPath ? asset('storage/' . ltrim($faviconPath, '/')) : null;

        // Filesystem existence checks (helps debug)
        $logoExists = $logoPath ? Storage::disk('public')->exists($logoPath) : false;
        $faviconExists = $faviconPath ? Storage::disk('public')->exists($faviconPath) : false;
    @endphp

    <div class="flex flex-col gap-8">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="rounded-xl bg-gradient-to-r from-blue-500 to-blue-600 p-3">
                    <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                </div>
                <div>
                    <h1 class="text-2xl font-bold text-slate-900">Settings</h1>
                    <p class="text-sm text-slate-500">Configure website settings and preferences</p>
                </div>
            </div>
        </div>

        @if(session('success'))
            <div class="rounded-xl bg-gradient-to-r from-emerald-50 to-green-50 border border-emerald-200 p-4 flex items-start">
                <svg class="h-5 w-5 text-emerald-500 mt-0.5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <div class="flex-1">
                    <p class="text-emerald-800 font-medium">{{ session('success') }}</p>
                    <p class="text-emerald-600 text-sm mt-1">Settings have been updated successfully.</p>
                </div>
            </div>
        @endif

        <form method="POST" action="{{ route('admin.settings.update') }}" enctype="multipart/form-data" class="space-y-8">
            @csrf
            @method('PUT')

            <!-- Branding Section -->
            <div class="rounded-xl border border-slate-200 bg-white shadow-lg overflow-hidden">
                <div class="border-b border-slate-200 bg-gradient-to-r from-slate-50 to-white px-6 py-4">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="rounded-lg bg-blue-100 p-2">
                                <svg class="h-5 w-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 4v16M17 4v16M3 8h4m10 0h4M3 12h18M3 16h4m10 0h4M4 20h16a1 1 0 001-1V5a1 1 0 00-1-1H4a1 1 0 00-1 1v14a1 1 0 001 1z"></path>
                                </svg>
                            </div>
                            <div>
                                <h2 class="text-lg font-semibold text-slate-900">{{ __('common.settings.branding') }}</h2>
                                <p class="text-sm text-slate-500">{{ __('common.settings.branding_hint') }}</p>
                            </div>
                        </div>
                        <span class="rounded-full bg-blue-100 px-3 py-1 text-xs font-medium text-blue-700">Required</span>
                    </div>
                </div>

                <div class="p-6 space-y-6">
                    <!-- Site Names -->
                    <div class="grid gap-6 md:grid-cols-2">
                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-slate-700" for="site_name_am">
                                {{ __('common.labels.title') }} (AM)
                            </label>
                            <div class="relative">
                                <input
                                    type="text"
                                    id="site_name_am"
                                    name="site_name_am"
                                    value="{{ old('site_name_am', $branding['site_name_am'] ?? '') }}"
                                    class="w-full rounded-lg border border-slate-300 bg-white px-4 py-3 text-slate-700 placeholder-slate-400 transition-all focus:border-blue-500 focus:ring-2 focus:ring-blue-200 focus:ring-opacity-50"
                                    placeholder="Enter site name in Armenian"
                                >
                                @error('site_name_am')
                                    <div class="absolute right-3 top-3">
                                        <svg class="h-5 w-5 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.998-.833-2.732 0L4.346 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                        </svg>
                                    </div>
                                @enderror
                            </div>
                            @error('site_name_am')
                                <p class="text-sm text-rose-600 flex items-center gap-1">
                                    <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                                    </svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-slate-700" for="site_name_en">
                                {{ __('common.labels.title') }} (EN)
                            </label>
                            <div class="relative">
                                <input
                                    type="text"
                                    id="site_name_en"
                                    name="site_name_en"
                                    value="{{ old('site_name_en', $branding['site_name_en'] ?? '') }}"
                                    class="w-full rounded-lg border border-slate-300 bg-white px-4 py-3 text-slate-700 placeholder-slate-400 transition-all focus:border-blue-500 focus:ring-2 focus:ring-blue-200 focus:ring-opacity-50"
                                    placeholder="Enter site name in English"
                                >
                                @error('site_name_en')
                                    <div class="absolute right-3 top-3">
                                        <svg class="h-5 w-5 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.998-.833-2.732 0L4.346 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                        </svg>
                                    </div>
                                @enderror
                            </div>
                            @error('site_name_en')
                                <p class="text-sm text-rose-600 flex items-center gap-1">
                                    <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                                    </svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>
                    </div>

                    <!-- Logo and Favicon -->
                    <div class="grid gap-6 md:grid-cols-2">
                        <!-- Logo Upload -->
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-slate-700" for="logo">
                                    {{ __('common.labels.logo') }}
                                </label>
                                <p class="text-sm text-slate-500 mt-1">Recommended: PNG format, transparent background</p>
                            </div>
                            
                            <div class="relative">
                                <label for="logo" class="block">
                                    <div class="flex cursor-pointer flex-col items-center justify-center rounded-lg border-2 border-dashed border-slate-300 bg-slate-50 p-6 hover:border-blue-400 hover:bg-blue-50 transition-colors">
                                        <svg class="mb-3 h-8 w-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                        <span class="text-sm font-medium text-slate-700">Click to upload logo</span>
                                        <span class="mt-1 text-xs text-slate-500">or drag and drop</span>
                                    </div>
                                    <input
                                        type="file"
                                        name="logo"
                                        id="logo"
                                        class="hidden"
                                        accept="image/*"
                                    >
                                </label>
                            </div>

                            @if(!empty($logoPath))
                                <div class="mt-4">
                                    <p class="text-sm font-medium text-slate-700 mb-2">Current Logo</p>
                                    <div class="flex items-center gap-4 rounded-lg border border-slate-200 bg-white p-4">
                                        <div class="relative overflow-hidden rounded-lg border border-slate-200 bg-white p-2">
                                            <img
                                                src="{{ $logoUrl }}"
                                                alt="Logo"
                                                class="h-16 w-auto object-contain"
                                                onerror="this.onerror=null; this.src='data:image/svg+xml;utf8,<svg xmlns=%22http://www.w3.org/2000/svg%22 width=%22160%22 height=%2260%22><rect width=%22100%25%22 height=%22100%25%22 fill=%22%23f1f5f9%22/><text x=%2250%25%22 y=%2255%25%22 dominant-baseline=%22middle%22 text-anchor=%22middle%22 fill=%22%2364748b%22 font-size=%2212%22>Logo not found</text></svg>';"
                                            >
                                        </div>
                                        <div class="flex-1">
                                            <span class="block text-sm text-slate-600">Currently active</span>
                                            @if($debug)
                                                <div class="mt-2 space-y-1">
                                                    <span class="inline-flex items-center gap-1 text-xs text-slate-500">
                                                        <span class="h-1.5 w-1.5 rounded-full {{ $logoExists ? 'bg-emerald-500' : 'bg-rose-500' }}"></span>
                                                        File exists: {{ $logoExists ? 'Yes' : 'No' }}
                                                    </span>
                                                    <span class="block text-xs text-slate-400 truncate max-w-xs">Path: {{ $logoPath }}</span>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endif

                            @error('logo')
                                <p class="text-sm text-rose-600 flex items-center gap-1">
                                    <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                                    </svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- Favicon Upload -->
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-slate-700" for="favicon">
                                    {{ __('common.settings.favicon') }}
                                </label>
                                <p class="text-sm text-slate-500 mt-1">Recommended: ICO or PNG, 32×32 or 64×64</p>
                            </div>
                            
                            <div class="relative">
                                <label for="favicon" class="block">
                                    <div class="flex cursor-pointer flex-col items-center justify-center rounded-lg border-2 border-dashed border-slate-300 bg-slate-50 p-6 hover:border-blue-400 hover:bg-blue-50 transition-colors">
                                        <svg class="mb-3 h-8 w-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path>
                                        </svg>
                                        <span class="text-sm font-medium text-slate-700">Click to upload favicon</span>
                                        <span class="mt-1 text-xs text-slate-500">.ico, .png formats</span>
                                    </div>
                                    <input
                                        type="file"
                                        name="favicon"
                                        id="favicon"
                                        class="hidden"
                                        accept="image/*"
                                    >
                                </label>
                            </div>

                            @if(!empty($faviconPath))
                                <div class="mt-4">
                                    <p class="text-sm font-medium text-slate-700 mb-2">Current Favicon</p>
                                    <div class="flex items-center gap-4 rounded-lg border border-slate-200 bg-white p-4">
                                        <div class="relative overflow-hidden rounded-lg border border-slate-200 bg-white p-2">
                                            <img
                                                src="{{ $faviconUrl }}"
                                                alt="Favicon"
                                                class="h-12 w-12 object-contain"
                                                onerror="this.onerror=null; this.src='data:image/svg+xml;utf8,<svg xmlns=%22http://www.w3.org/2000/svg%22 width=%2240%22 height=%2240%22><rect width=%22100%25%22 height=%22100%25%22 fill=%22%23f1f5f9%22/><text x=%2250%25%22 y=%2255%25%22 dominant-baseline=%22middle%22 text-anchor=%22middle%22 fill=%22%2364748b%22 font-size=%2210%22>404</text></svg>';"
                                            >
                                        </div>
                                        <div class="flex-1">
                                            <span class="block text-sm text-slate-600">Browser tab icon</span>
                                            @if($debug)
                                                <div class="mt-2 space-y-1">
                                                    <span class="inline-flex items-center gap-1 text-xs text-slate-500">
                                                        <span class="h-1.5 w-1.5 rounded-full {{ $faviconExists ? 'bg-emerald-500' : 'bg-rose-500' }}"></span>
                                                        File exists: {{ $faviconExists ? 'Yes' : 'No' }}
                                                    </span>
                                                    <span class="block text-xs text-slate-400 truncate max-w-xs">Path: {{ $faviconPath }}</span>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endif

                            @error('favicon')
                                <p class="text-sm text-rose-600 flex items-center gap-1">
                                    <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                                    </svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- Contact Section -->
            <div class="rounded-xl border border-slate-200 bg-white shadow-lg overflow-hidden">
                <div class="border-b border-slate-200 bg-gradient-to-r from-slate-50 to-white px-6 py-4">
                    <div class="flex items-center gap-3">
                        <div class="rounded-lg bg-emerald-100 p-2">
                            <svg class="h-5 w-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-lg font-semibold text-slate-900">{{ __('common.settings.contact') }}</h2>
                            <p class="text-sm text-slate-500">Contact information displayed on the website</p>
                        </div>
                    </div>
                </div>

                <div class="p-6 space-y-6">
                    <!-- Address -->
                    <div class="grid gap-6 md:grid-cols-2">
                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-slate-700" for="address_am">
                                {{ __('common.labels.address_am') }}
                            </label>
                            <input
                                type="text"
                                id="address_am"
                                name="address_am"
                                value="{{ old('address_am', $contact['address_am'] ?? '') }}"
                                class="w-full rounded-lg border border-slate-300 bg-white px-4 py-3 text-slate-700 placeholder-slate-400 transition-all focus:border-blue-500 focus:ring-2 focus:ring-blue-200 focus:ring-opacity-50"
                                placeholder="Armenian address"
                            >
                        </div>
                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-slate-700" for="address_en">
                                {{ __('common.labels.address_en') }}
                            </label>
                            <input
                                type="text"
                                id="address_en"
                                name="address_en"
                                value="{{ old('address_en', $contact['address_en'] ?? '') }}"
                                class="w-full rounded-lg border border-slate-300 bg-white px-4 py-3 text-slate-700 placeholder-slate-400 transition-all focus:border-blue-500 focus:ring-2 focus:ring-blue-200 focus:ring-opacity-50"
                                placeholder="English address"
                            >
                        </div>
                    </div>

                    <!-- Contact Details -->
                    <div class="grid gap-6 md:grid-cols-3">
                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-slate-700" for="phone">
                                {{ __('common.labels.phone') }}
                            </label>
                            <div class="relative">
                                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                    <svg class="h-5 w-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                    </svg>
                                </div>
                                <input
                                    type="text"
                                    id="phone"
                                    name="phone"
                                    value="{{ old('phone', $contact['phone'] ?? '') }}"
                                    class="w-full rounded-lg border border-slate-300 bg-white pl-10 pr-4 py-3 text-slate-700 placeholder-slate-400 transition-all focus:border-blue-500 focus:ring-2 focus:ring-blue-200 focus:ring-opacity-50"
                                    placeholder="+374 00 000000"
                                >
                            </div>
                        </div>

                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-slate-700" for="email">
                                {{ __('common.labels.email') }}
                            </label>
                            <div class="relative">
                                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                    <svg class="h-5 w-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                    </svg>
                                </div>
                                <input
                                    type="email"
                                    id="email"
                                    name="email"
                                    value="{{ old('email', $contact['email'] ?? '') }}"
                                    class="w-full rounded-lg border border-slate-300 bg-white pl-10 pr-4 py-3 text-slate-700 placeholder-slate-400 transition-all focus:border-blue-500 focus:ring-2 focus:ring-blue-200 focus:ring-opacity-50"
                                    placeholder="contact@example.com"
                                >
                            </div>
                        </div>

                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-slate-700" for="working_hours_en">
                                {{ __('common.gov.office_hours') }} (EN)
                            </label>
                            <input
                                type="text"
                                id="working_hours_en"
                                name="working_hours_en"
                                value="{{ old('working_hours_en', $contact['working_hours_en'] ?? '') }}"
                                class="w-full rounded-lg border border-slate-300 bg-white px-4 py-3 text-slate-700 placeholder-slate-400 transition-all focus:border-blue-500 focus:ring-2 focus:ring-blue-200 focus:ring-opacity-50"
                                placeholder="Mon-Fri 9:00-18:00"
                            >
                        </div>
                    </div>

                    <!-- Working Hours AM -->
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-slate-700" for="working_hours_am">
                            {{ __('common.gov.office_hours') }} (AM)
                        </label>
                        <input
                            type="text"
                            id="working_hours_am"
                            name="working_hours_am"
                            value="{{ old('working_hours_am', $contact['working_hours_am'] ?? '') }}"
                            class="w-full rounded-lg border border-slate-300 bg-white px-4 py-3 text-slate-700 placeholder-slate-400 transition-all focus:border-blue-500 focus:ring-2 focus:ring-blue-200 focus:ring-opacity-50"
                            placeholder="Երկ-Ուրբ 9:00-18:00"
                        >
                    </div>
                </div>
            </div>

            @php
                $seoValues = $seo ?? [];
            @endphp

            <!-- SEO & Meta Section -->
            <div class="rounded-xl border border-slate-200 bg-white shadow-lg overflow-hidden">
                <div class="border-b border-slate-200 bg-gradient-to-r from-slate-50 to-white px-6 py-4">
                    <div class="flex items-center gap-3">
                        <div class="rounded-lg bg-orange-100 p-2">
                            <svg class="h-5 w-5 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6M4 4h16v16H4z" />
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-lg font-semibold text-slate-900">SEO & Meta defaults</h2>
                            <p class="text-sm text-slate-500">Control common meta tags and verification values for public visitors.</p>
                        </div>
                        <span class="rounded-full bg-amber-100 px-3 py-1 text-xs font-medium text-amber-700">Optional</span>
                    </div>
                </div>

                <div class="p-6 space-y-6">
                    <div class="grid gap-6 md:grid-cols-2">
                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-slate-700" for="description_am">
                                Meta description (AM)
                            </label>
                            <textarea
                                id="description_am"
                                name="description_am"
                                rows="3"
                                class="w-full rounded-lg border border-slate-300 bg-white px-4 py-3 text-slate-700 placeholder-slate-400 transition-all focus:border-orange-500 focus:ring-2 focus:ring-orange-200 focus:ring-opacity-50"
                            >{{ old('description_am', $seoValues['description_am'] ?? '') }}</textarea>
                            @error('description_am')
                                <p class="text-sm text-rose-600 flex items-center gap-1">
                                    <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                                    </svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>
                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-slate-700" for="description_en">
                                Meta description (EN)
                            </label>
                            <textarea
                                id="description_en"
                                name="description_en"
                                rows="3"
                                class="w-full rounded-lg border border-slate-300 bg-white px-4 py-3 text-slate-700 placeholder-slate-400 transition-all focus:border-orange-500 focus:ring-2 focus:ring-orange-200 focus:ring-opacity-50"
                            >{{ old('description_en', $seoValues['description_en'] ?? '') }}</textarea>
                            @error('description_en')
                                <p class="text-sm text-rose-600 flex items-center gap-1">
                                    <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                                    </svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>
                    </div>

                    <div class="grid gap-6 md:grid-cols-2">
                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-slate-700" for="google_verification">
                                Google verification code
                            </label>
                            <input
                                type="text"
                                id="google_verification"
                                name="google_verification"
                                value="{{ old('google_verification', $seoValues['google_verification'] ?? '') }}"
                                class="w-full rounded-lg border border-slate-300 bg-white px-4 py-3 text-slate-700 placeholder-slate-400 transition-all focus:border-orange-500 focus:ring-2 focus:ring-orange-200 focus:ring-opacity-50"
                                placeholder="google-site-verification token"
                            >
                            <p class="text-xs text-slate-500">Paste only the content value provided by Google Search Console.</p>
                            @error('google_verification')
                                <p class="text-sm text-rose-600 flex items-center gap-1">
                                    <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                                    </svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>
                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-slate-700" for="bing_verification">
                                Bing verification code
                            </label>
                            <input
                                type="text"
                                id="bing_verification"
                                name="bing_verification"
                                value="{{ old('bing_verification', $seoValues['bing_verification'] ?? '') }}"
                                class="w-full rounded-lg border border-slate-300 bg-white px-4 py-3 text-slate-700 placeholder-slate-400 transition-all focus:border-orange-500 focus:ring-2 focus:ring-orange-200 focus:ring-opacity-50"
                                placeholder="Bing verification token"
                            >
                            <p class="text-xs text-slate-500">Paste only the content value provided by Bing Webmaster Tools.</p>
                            @error('bing_verification')
                                <p class="text-sm text-rose-600 flex items-center gap-1">
                                    <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                                    </svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- Notifications Section -->
            <div class="rounded-xl border border-slate-200 bg-white shadow-lg overflow-hidden">
                <div class="border-b border-slate-200 bg-gradient-to-r from-slate-50 to-white px-6 py-4">
                    <div class="flex items-center gap-3">
                        <div class="rounded-lg bg-amber-100 p-2">
                            <svg class="h-5 w-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-lg font-semibold text-slate-900">{{ __('common.settings.notifications') }}</h2>
                            <p class="text-sm text-slate-500">Configure notification settings</p>
                        </div>
                    </div>
                </div>

                <div class="p-6 space-y-6">
                    <div class="grid gap-6 md:grid-cols-2">
                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-slate-700" for="admin_email">
                                {{ __('common.labels.email') }} (Admin)
                            </label>
                            <div class="relative">
                                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                    <svg class="h-5 w-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"></path>
                                    </svg>
                                </div>
                                <input
                                    type="email"
                                    id="admin_email"
                                    name="admin_email"
                                    value="{{ old('admin_email', $notifications['admin_email'] ?? '') }}"
                                    class="w-full rounded-lg border border-slate-300 bg-white pl-10 pr-4 py-3 text-slate-700 placeholder-slate-400 transition-all focus:border-blue-500 focus:ring-2 focus:ring-blue-200 focus:ring-opacity-50"
                                    placeholder="admin@example.com"
                                >
                            </div>
                        </div>

                        <div class="space-y-4">
                            <!-- Enable Email -->
                            <div class="flex items-center justify-between rounded-lg border border-slate-200 bg-slate-50 p-4">
                                <div class="flex items-center gap-3">
                                    <div class="rounded-lg bg-blue-100 p-1.5">
                                        <svg class="h-4 w-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <label class="text-sm font-medium text-slate-700" for="enable_email">
                                            {{ __('common.settings.enable_email') }}
                                        </label>
                                        <p class="text-xs text-slate-500">Send email notifications</p>
                                    </div>
                                </div>
                                <input type="hidden" name="enable_email" value="0">
                                <label class="relative inline-flex cursor-pointer items-center">
                                    <input
                                        type="checkbox"
                                        id="enable_email"
                                        name="enable_email"
                                        value="1"
                                        {{ old('enable_email', $notifications['enable_email'] ?? false) ? 'checked' : '' }}
                                        class="peer sr-only"
                                    >
                                    <div class="h-6 w-11 rounded-full bg-slate-300 after:absolute after:top-0.5 after:left-0.5 after:h-5 after:w-5 after:rounded-full after:bg-white after:transition-all after:content-[''] peer-checked:bg-blue-600 peer-checked:after:translate-x-full"></div>
                                </label>
                            </div>

                            <!-- Enable SMS -->
                            <div class="flex items-center justify-between rounded-lg border border-slate-200 bg-slate-50 p-4">
                                <div class="flex items-center gap-3">
                                    <div class="rounded-lg bg-green-100 p-1.5">
                                        <svg class="h-4 w-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <label class="text-sm font-medium text-slate-700" for="enable_sms">
                                            {{ __('common.settings.enable_sms') }}
                                        </label>
                                        <p class="text-xs text-slate-500">Send SMS notifications</p>
                                    </div>
                                </div>
                                <input type="hidden" name="enable_sms" value="0">
                                <label class="relative inline-flex cursor-pointer items-center">
                                    <input
                                        type="checkbox"
                                        id="enable_sms"
                                        name="enable_sms"
                                        value="1"
                                        {{ old('enable_sms', $notifications['enable_sms'] ?? false) ? 'checked' : '' }}
                                        class="peer sr-only"
                                    >
                                    <div class="h-6 w-11 rounded-full bg-slate-300 after:absolute after:top-0.5 after:left-0.5 after:h-5 after:w-5 after:rounded-full after:bg-white after:transition-all after:content-[''] peer-checked:bg-green-600 peer-checked:after:translate-x-full"></div>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Analytics Section -->
            <div class="rounded-xl border border-slate-200 bg-white shadow-lg overflow-hidden">
                <div class="border-b border-slate-200 bg-gradient-to-r from-slate-50 to-white px-6 py-4">
                    <div class="flex items-center gap-3">
                        <div class="rounded-lg bg-purple-100 p-2">
                            <svg class="h-5 w-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-lg font-semibold text-slate-900">{{ __('common.settings.analytics') }}</h2>
                            <p class="text-sm text-slate-500">Website analytics and tracking</p>
                        </div>
                    </div>
                </div>

                <div class="p-6">
                    <div class="flex items-center justify-between rounded-lg border border-slate-200 bg-slate-50 p-4">
                        <div class="flex items-center gap-3">
                            <div class="rounded-lg bg-purple-100 p-1.5">
                                <svg class="h-4 w-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                                </svg>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-slate-700" for="analytics_enabled">
                                    {{ __('common.settings.enable_analytics') }}
                                </label>
                                <p class="text-xs text-slate-500">Enable website traffic tracking</p>
                            </div>
                        </div>
                        <input type="hidden" name="analytics_enabled" value="0">
                        <label class="relative inline-flex cursor-pointer items-center">
                            <input
                                type="checkbox"
                                id="analytics_enabled"
                                name="analytics_enabled"
                                value="1"
                                {{ old('analytics_enabled', $analytics['enabled'] ?? false) ? 'checked' : '' }}
                                class="peer sr-only"
                            >
                            <div class="h-6 w-11 rounded-full bg-slate-300 after:absolute after:top-0.5 after:left-0.5 after:h-5 after:w-5 after:rounded-full after:bg-white after:transition-all after:content-[''] peer-checked:bg-purple-600 peer-checked:after:translate-x-full"></div>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Footer Quick Links Section -->
            <div class="rounded-xl border border-slate-200 bg-white shadow-lg overflow-hidden">
                <div class="border-b border-slate-200 bg-gradient-to-r from-slate-50 to-white px-6 py-4">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="rounded-lg bg-indigo-100 p-2">
                                <svg class="h-5 w-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path>
                                </svg>
                            </div>
                            <div>
                                <h2 class="text-lg font-semibold text-slate-900">{{ __('common.settings.footer_quick_links') }}</h2>
                                <p class="text-sm text-slate-500">{{ __('common.settings.footer_hint') }}</p>
                            </div>
                        </div>
                        <span class="rounded-full bg-indigo-100 px-3 py-1 text-xs font-medium text-indigo-700">5 Links Max</span>
                    </div>
                </div>

                <div class="p-6 space-y-4">
                    @php
                        $footerLinks = $footer['quick_links'] ?? [];
                        $footerLinksCount = 5;
                    @endphp

                    @for ($i = 0; $i < $footerLinksCount; $i++)
                        @php
                            $link = $footerLinks[$i] ?? ['label_am' => '', 'label_en' => '', 'url' => ''];
                        @endphp

                        <div class="rounded-xl border border-slate-200 bg-gradient-to-r from-slate-50 to-white p-5">
                            <div class="mb-3 flex items-center gap-2">
                                <span class="flex h-6 w-6 items-center justify-center rounded-full bg-indigo-100 text-xs font-semibold text-indigo-700">
                                    {{ $i + 1 }}
                                </span>
                                <span class="text-sm font-medium text-slate-700">Quick Link {{ $i + 1 }}</span>
                            </div>
                            
                            <div class="grid gap-4 md:grid-cols-3">
                                <div class="space-y-2">
                                    <label class="text-xs font-medium uppercase tracking-wide text-slate-500">
                                        AM {{ __('common.labels.title') }}
                                    </label>
                                    <input
                                        type="text"
                                        name="quick_links[{{ $i }}][label_am]"
                                        value="{{ old("quick_links.{$i}.label_am", $link['label_am'] ?? '') }}"
                                        class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2.5 text-sm text-slate-700 placeholder-slate-400 transition-all focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200"
                                        placeholder="Armenian label"
                                    >
                                </div>
                                <div class="space-y-2">
                                    <label class="text-xs font-medium uppercase tracking-wide text-slate-500">
                                        EN {{ __('common.labels.title') }}
                                    </label>
                                    <input
                                        type="text"
                                        name="quick_links[{{ $i }}][label_en]"
                                        value="{{ old("quick_links.{$i}.label_en", $link['label_en'] ?? '') }}"
                                        class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2.5 text-sm text-slate-700 placeholder-slate-400 transition-all focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200"
                                        placeholder="English label"
                                    >
                                </div>
                                <div class="space-y-2">
                                    <label class="text-xs font-medium uppercase tracking-wide text-slate-500">
                                        URL
                                    </label>
                                    <div class="relative">
                                        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                            <svg class="h-4 w-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path>
                                            </svg>
                                        </div>
                                        <input
                                            type="text"
                                            name="quick_links[{{ $i }}][url]"
                                            value="{{ old("quick_links.{$i}.url", $link['url'] ?? '') }}"
                                            class="w-full rounded-lg border border-slate-300 bg-white pl-10 pr-3 py-2.5 text-sm text-slate-700 placeholder-slate-400 transition-all focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200"
                                            placeholder="https://example.com/page"
                                        >
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endfor
                </div>
            </div>

            <!-- Social Links Section -->
            <div class="rounded-xl border border-slate-200 bg-white shadow-lg overflow-hidden">
                <div class="border-b border-slate-200 bg-gradient-to-r from-slate-50 to-white px-6 py-4">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="rounded-lg bg-pink-100 p-2">
                                <svg class="h-5 w-5 text-pink-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"></path>
                                </svg>
                            </div>
                            <div>
                                <h2 class="text-lg font-semibold text-slate-900">{{ __('common.settings.social_links') }}</h2>
                                <p class="text-sm text-slate-500">Social media links for the footer</p>
                            </div>
                        </div>
                        <span class="rounded-full bg-pink-100 px-3 py-1 text-xs font-medium text-pink-700">5 Links Max</span>
                    </div>
                </div>

                <div class="p-6 space-y-4">
                    @php
                        $socialLinks = $footer['social_links'] ?? [];
                        $socialLinksCount = 5;
                    @endphp

                    @for ($i = 0; $i < $socialLinksCount; $i++)
                        @php
                            $link = $socialLinks[$i] ?? ['label_am' => '', 'label_en' => '', 'url' => ''];
                        @endphp

                        <div class="rounded-xl border border-slate-200 bg-gradient-to-r from-slate-50 to-white p-5">
                            <div class="mb-3 flex items-center gap-2">
                                <span class="flex h-6 w-6 items-center justify-center rounded-full bg-pink-100 text-xs font-semibold text-pink-700">
                                    {{ $i + 1 }}
                                </span>
                                <span class="text-sm font-medium text-slate-700">Social Link {{ $i + 1 }}</span>
                            </div>
                            
                            <div class="grid gap-4 md:grid-cols-3">
                                <div class="space-y-2">
                                    <label class="text-xs font-medium uppercase tracking-wide text-slate-500">
                                        AM {{ __('common.labels.title') }}
                                    </label>
                                    <input
                                        type="text"
                                        name="social_links[{{ $i }}][label_am]"
                                        value="{{ old("social_links.{$i}.label_am", $link['label_am'] ?? '') }}"
                                        class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2.5 text-sm text-slate-700 placeholder-slate-400 transition-all focus:border-pink-500 focus:ring-2 focus:ring-pink-200"
                                        placeholder="Facebook, Twitter, etc."
                                    >
                                </div>
                                <div class="space-y-2">
                                    <label class="text-xs font-medium uppercase tracking-wide text-slate-500">
                                        EN {{ __('common.labels.title') }}
                                    </label>
                                    <input
                                        type="text"
                                        name="social_links[{{ $i }}][label_en]"
                                        value="{{ old("social_links.{$i}.label_en", $link['label_en'] ?? '') }}"
                                        class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2.5 text-sm text-slate-700 placeholder-slate-400 transition-all focus:border-pink-500 focus:ring-2 focus:ring-pink-200"
                                        placeholder="Facebook, Twitter, etc."
                                    >
                                </div>
                                <div class="space-y-2">
                                    <label class="text-xs font-medium uppercase tracking-wide text-slate-500">
                                        URL
                                    </label>
                                    <div class="relative">
                                        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                            <svg class="h-4 w-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path>
                                            </svg>
                                        </div>
                                        <input
                                            type="text"
                                            name="social_links[{{ $i }}][url]"
                                            value="{{ old("social_links.{$i}.url", $link['url'] ?? '') }}"
                                            class="w-full rounded-lg border border-slate-300 bg-white pl-10 pr-3 py-2.5 text-sm text-slate-700 placeholder-slate-400 transition-all focus:border-pink-500 focus:ring-2 focus:ring-pink-200"
                                            placeholder="https://facebook.com/username"
                                        >
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endfor
                </div>
            </div>

            <!-- Privacy Policy Section -->
            <div class="rounded-xl border border-slate-200 bg-white shadow-lg overflow-hidden">
                <div class="border-b border-slate-200 bg-gradient-to-r from-slate-50 to-white px-6 py-4">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="rounded-lg bg-amber-100 p-2">
                                <svg class="h-5 w-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 10-8 0v4a4 4 0 00-4 4v1h16v-1a4 4 0 00-4-4V7z"></path>
                                </svg>
                            </div>
                            <div>
                                <h2 class="text-lg font-semibold text-slate-900">Privacy Policy</h2>
                                <p class="text-sm text-slate-500">Update the page content displayed to the public</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="p-6 space-y-6">
                    <div class="grid gap-6 md:grid-cols-2">
                        <div>
                            <label class="block text-sm font-medium text-slate-700" for="privacy_title_am">
                                Title (AM)
                            </label>
                            <input
                                type="text"
                                name="privacy_title_am"
                                id="privacy_title_am"
                                class="mt-2 w-full rounded-lg border border-slate-300 bg-white px-4 py-3 text-slate-700 focus:border-amber-500 focus:ring-2 focus:ring-amber-200"
                                value="{{ old('privacy_title_am', $privacyPolicy->title_am ?? '') }}"
                                placeholder="የግል መረጃ ፖሊሲ"
                            >
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700" for="privacy_title_en">
                                Title (EN)
                            </label>
                            <input
                                type="text"
                                name="privacy_title_en"
                                id="privacy_title_en"
                                class="mt-2 w-full rounded-lg border border-slate-300 bg-white px-4 py-3 text-slate-700 focus:border-amber-500 focus:ring-2 focus:ring-amber-200"
                                value="{{ old('privacy_title_en', $privacyPolicy->title_en ?? '') }}"
                                placeholder="Privacy Policy"
                            >
                        </div>
                    </div>

                        <div class="grid gap-6 md:grid-cols-2">
                            <div>
                                <label class="block text-sm font-medium text-slate-700" for="privacy_body_am">
                                    Body (AM)
                                </label>
                                <textarea
                                    id="privacy_body_am"
                                    name="privacy_body_am"
                                    rows="6"
                                    class="mt-2 w-full rounded-lg border border-slate-300 bg-white px-4 py-3 text-sm text-slate-700 placeholder-slate-400 focus:border-amber-500 focus:ring-2 focus:ring-amber-200 transition js-editor"
                                    data-editor="tinymce"
                                >{{ old('privacy_body_am', $privacyPolicy->body_am ?? '') }}</textarea>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-700" for="privacy_body_en">
                                    Body (EN)
                                </label>
                                <textarea
                                    id="privacy_body_en"
                                    name="privacy_body_en"
                                    rows="6"
                                    class="mt-2 w-full rounded-lg border border-slate-300 bg-white px-4 py-3 text-sm text-slate-700 placeholder-slate-400 focus:border-amber-500 focus:ring-2 focus:ring-amber-200 transition js-editor"
                                    data-editor="tinymce"
                                >{{ old('privacy_body_en', $privacyPolicy->body_en ?? '') }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>

            <!-- Save Button -->
            <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-lg">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="rounded-lg bg-gradient-to-r from-blue-500 to-blue-600 p-2">
                            <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-slate-700">Ready to save changes?</p>
                            <p class="text-xs text-slate-500">All settings will be updated immediately</p>
                        </div>
                    </div>
                    <button
                        type="submit"
                        class="inline-flex items-center gap-2 rounded-lg bg-gradient-to-r from-blue-600 to-blue-700 px-6 py-3 text-sm font-semibold text-white transition-all hover:from-blue-700 hover:to-blue-800 hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
                    >
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path>
                        </svg>
                        {{ __('common.actions.save') }}
                    </button>
                </div>
            </div>
        </form>
    </div>

    <style>
        * {
            transition: background-color 0.2s ease, color 0.2s ease, border-color 0.2s ease, transform 0.2s ease;
        }

        input[type="checkbox"]:checked + div {
            background-color: #3b82f6;
        }

        input[type="checkbox"]:checked + div:after {
            transform: translateX(100%);
        }

        .focus\:ring-2:focus {
            --tw-ring-opacity: 0.2;
        }

        /* Smooth file upload hover */
        label[for="logo"] div:hover,
        label[for="favicon"] div:hover {
            border-color: #60a5fa;
            background-color: #dbeafe;
        }
    </style>

    <script>
        // File upload preview
        document.addEventListener('DOMContentLoaded', function() {
            const logoInput = document.getElementById('logo');
            const faviconInput = document.getElementById('favicon');
            
            if (logoInput) {
                logoInput.addEventListener('change', function(e) {
                    const fileName = e.target.files[0]?.name;
                    const label = this.previousElementSibling;
                    if (fileName && label) {
                        label.innerHTML = `
                            <div class="flex flex-col items-center justify-center p-6">
                                <svg class="mb-3 h-8 w-8 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                <span class="text-sm font-medium text-emerald-700">${fileName}</span>
                                <span class="mt-1 text-xs text-slate-500">Click to change logo</span>
                            </div>
                        `;
                    }
                });
            }
            
            if (faviconInput) {
                faviconInput.addEventListener('change', function(e) {
                    const fileName = e.target.files[0]?.name;
                    const label = this.previousElementSibling;
                    if (fileName && label) {
                        label.innerHTML = `
                            <div class="flex flex-col items-center justify-center p-6">
                                <svg class="mb-3 h-8 w-8 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                <span class="text-sm font-medium text-emerald-700">${fileName}</span>
                                <span class="mt-1 text-xs text-slate-500">Click to change favicon</span>
                            </div>
                        `;
                    }
                });
            }
        });
    </script>
@include('admin.components.editor-scripts')

@endsection
