<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        @php
            $branding = $site_settings['site.branding'] ?? [];
            $siteName = $branding['site_name_' . app()->getLocale()] ?? config('app.name', 'Laravel');
        @endphp
        <title>{{ $siteName }} · {{ __('ui.dashboard') }}</title>
        @include('partials.favicon')
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @stack('meta')
    </head>
    <body
        class="min-h-screen bg-[#f4f6fb]  text-slate-900"
        data-editor-upload-url="{{ route('admin.editor.upload') }}"
    >
        <div x-data="{ sidebarOpen: false }" class="flex min-h-screen">
            <div
                x-show="sidebarOpen"
                x-cloak
                x-transition.opacity
                class="fixed inset-0 z-30 bg-slate-900/60 lg:hidden"
                @click="sidebarOpen = false"
            ></div>

            <div class="hidden lg:flex lg:w-72 lg:flex-shrink-0">
                @include('admin.layouts.sidebar')
            </div>

            <div class="lg:hidden">
                <div
                    x-show="sidebarOpen"
                    x-cloak
                    x-transition.opacity
                    class="fixed inset-0 z-40 flex"
                >
                    <div class="absolute inset-0 bg-black/30" @click="sidebarOpen = false"></div>
                    <div class="relative z-40 flex w-72">
                        @include('admin.layouts.sidebar', ['mobile' => true])
                    </div>
                </div>
            </div>

            <div class="flex flex-1 flex-col">
                @include('admin.layouts.topbar')

                <main class="flex-1 overflow-y-auto bg-[#f4f6fb]">
                    <div class="w-full space-y-6 px-4 py-6 sm:px-6 lg:px-10">
                        <x-breadcrumbs
                            class="mb-4"
                            :home-url="route('admin.dashboard')"
                            :home-label="__('ui.dashboard')"
                            :skip-segments="['admin']"
                        />

                        @if(session('success'))
                            <div class="rounded-2xl border border-emerald-200 bg-emerald-50 p-4  font-semibold text-emerald-700" role="alert">
                                {{ session('success') }}
                            </div>
                        @endif

                        @if ($errors->any())
                            <div class="rounded-2xl border border-rose-200 bg-rose-50 p-4  text-rose-700" role="alert">
                                <p class="font-semibold">{{ __('common.messages.validation_error') }}</p>
                                <ul class="mt-2 list-disc space-y-1 pl-5 text-xs">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        {{ $slot ?? '' }}
                        @yield('content')
                    </div>
                </main>
            </div>
        </div>

        @stack('scripts')
        @stack('tinymce-scripts')
    </body>
</html>

