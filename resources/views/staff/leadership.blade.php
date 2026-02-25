@php
    $seoMeta = [
        'title' => 'Leadership | Staff Directory',
        'description' => 'Meet the leadership team and senior staff at Addis Ababa public service.',
        'url' => route('staff.leadership'),
        'canonical' => route('staff.leadership'),
    ];
@endphp

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('common.nav.leadership_staff') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            <div class="grid gap-6 md:grid-cols-3">
                @forelse ($staff as $member)
                    <a
                        href="{{ route('staff.show', $member) }}"
                        class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm transition hover:-translate-y-1"
                    >
                        @if ($member->photo_path)
                            <img
                                src="{{ asset('storage/' . $member->photo_path) }}"
                                alt="{{ $member->display_name }}"
                                class="h-40 w-full rounded-xl object-cover"
                                loading="lazy"
                            >
                        @endif
                        <h3 class="mt-4 text-lg font-semibold text-gray-900">{{ $member->display_name }}</h3>
                        <p class="text-sm text-gray-600">{{ $member->display_title }}</p>
                        @if ($member->department)
                            <p class="mt-2 text-xs uppercase tracking-wide text-gray-400">{{ $member->department->display_name }}</p>
                        @endif
                    </a>
                @empty
                    <div class="rounded-2xl border border-gray-200 bg-white p-8 text-center text-sm text-gray-500 md:col-span-3">
                        {{ __('common.messages.no_staff') }}
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>
