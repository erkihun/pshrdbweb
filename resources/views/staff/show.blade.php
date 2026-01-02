@php
    $seoMeta = [
        'title' => $staff->display_name,
        'description' => $staff->display_bio ?? $staff->display_title,
        'url' => route('staff.show', $staff),
        'canonical' => route('staff.show', $staff),
    ];
@endphp

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $staff->display_name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <article class="rounded-2xl border border-gray-200 bg-white p-8 shadow-sm">
                <div class="grid gap-6 md:grid-cols-[200px_1fr]">
                    <div>
                        @if ($staff->photo_path)
                            <img
                                src="{{ asset('storage/' . $staff->photo_path) }}"
                                alt="{{ $staff->display_name }}"
                                class="h-48 w-full rounded-2xl object-cover"
                                loading="lazy"
                            >
                        @else
                            <div class="h-48 w-full rounded-2xl bg-gray-100"></div>
                        @endif
                    </div>
                    <div>
                        <h1 class="text-2xl font-semibold text-gray-900">{{ $staff->display_name }}</h1>
                        <p class="mt-2 text-sm text-gray-600">{{ $staff->display_title }}</p>
                        @if ($staff->department)
                            <p class="mt-2 text-xs uppercase tracking-wide text-gray-400">{{ $staff->department->display_name }}</p>
                        @endif
                        @if ($staff->display_bio)
                            <p class="mt-4 text-sm text-gray-700 whitespace-pre-line">{{ $staff->display_bio }}</p>
                        @endif
                        <div class="mt-6 flex flex-wrap gap-4 text-sm text-gray-600">
                            @if ($staff->phone)
                                <span>{{ __('common.labels.phone') }}: {{ $staff->phone }}</span>
                            @endif
                            @if ($staff->email)
                                <span>{{ __('common.labels.email') }}: {{ $staff->email }}</span>
                            @endif
                        </div>
                        <div class="mt-6">
                            <a href="{{ route('staff.index') }}" class="text-sm font-semibold text-gray-600 hover:text-gray-900">
                                {{ __('common.actions.back') }}
                            </a>
                        </div>
                    </div>
                </div>
            </article>
        </div>
    </div>
</x-app-layout>
