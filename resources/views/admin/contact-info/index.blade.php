@extends('admin.layouts.app')

@section('content')
    <div class="space-y-6">
        <div>
            <p class="text-xs uppercase tracking-wide text-slate-400">{{ __('ui.optional_group') }}</p>
            <h1 class="text-2xl font-semibold text-slate-900">Contact information</h1>
            <p class="text-sm text-slate-500">
                Single active record that powers the public contact page. Edit details whenever schedules, phones, or maps change.
            </p>
        </div>

        @if($contactInfo)
            <div class="grid gap-6 lg:grid-cols-[1.1fr_0.9fr]">
                <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <p class="text-xs uppercase tracking-wide text-slate-400">Bureau</p>
                            <h2 class="text-2xl font-semibold text-slate-900">{{ $contactInfo->bureau_name }}</h2>
                        </div>
                        <div class="flex flex-col items-end gap-2">
                            <a href="{{ route('admin.contact-info.edit', $contactInfo) }}" class="rounded-full border border-slate-300 px-4 py-2 text-xs font-semibold text-slate-700 hover:border-slate-400 hover:text-slate-900">
                                Edit record
                            </a>
                            <form action="{{ route('admin.contact-info.destroy', $contactInfo) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-xs font-semibold text-rose-600 hover:underline">
                                    Deactivate
                                </button>
                            </form>
                        </div>
                    </div>

                    <div class="mt-6 space-y-4 text-sm text-slate-700">
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">Address</p>
                            <p class="mt-2">
                                {{ $contactInfo->physical_address }}<br>
                                {{ $contactInfo->city }}{{ $contactInfo->region ? ', '.$contactInfo->region : '' }}<br>
                                {{ $contactInfo->country }}{{ $contactInfo->postal_code ? ' Â· '.$contactInfo->postal_code : '' }}
                            </p>
                        </div>
                        <div class="grid gap-4 md:grid-cols-2">
                            <div>
                                <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">Phones</p>
                                @if($contactInfo->phone_primary)
                                    <p class="font-semibold text-slate-900">{{ $contactInfo->phone_primary }}</p>
                                @endif
                                @if($contactInfo->phone_secondary)
                                    <p class="font-semibold text-slate-900">{{ $contactInfo->phone_secondary }}</p>
                                @endif
                            </div>
                            <div>
                                <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">Emails</p>
                                @if($contactInfo->email_primary)
                                    <p class="font-semibold text-slate-900">{{ $contactInfo->email_primary }}</p>
                                @endif
                                @if($contactInfo->email_secondary)
                                    <p class="font-semibold text-slate-900">{{ $contactInfo->email_secondary }}</p>
                                @endif
                            </div>
                        </div>
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">Office hours</p>
                            <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">Schedules &amp; brief</p>
                            <div class="mt-2 space-y-2 text-sm text-slate-800">
                                {!! $contactInfo->sanitized_office_hours ?: '<p>Weekdays Aú 08:00 ƒ?" 17:00</p>' !!}
                            </div>
                        </div>
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">Social</p>
                            <div class="mt-2 flex flex-wrap gap-3 text-xs font-semibold text-blue-600">
                                @if($contactInfo->facebook_url)
                                    <span>Facebook</span>
                                @endif
                                @if($contactInfo->telegram_url)
                                    <span>Telegram</span>
                                @endif
                                @if($contactInfo->x_url)
                                    <span>X</span>
                                @endif
                                @if($contactInfo->linkedin_url)
                                    <span>LinkedIn</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs uppercase tracking-wide text-slate-400">Map</p>
                            <h2 class="text-lg font-semibold text-slate-900">Current embed</h2>
                        </div>
                    </div>
                    <div class="mt-5 h-64 overflow-hidden rounded-xl border border-slate-200">
                        @if($contactInfo->map_iframe_html)
                            {!! $contactInfo->map_iframe_html !!}
                        @elseif($contactInfo->map_embed_url)
                            <iframe
                                class="h-full w-full border-0"
                                loading="lazy"
                                src="{{ $contactInfo->map_embed_url }}"
                                title="Contact map preview"
                                allowfullscreen
                            ></iframe>
                        @else
                            <div class="flex h-full items-center justify-center text-sm text-slate-500">
                                Map iframe not configured
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @else
            <div class="rounded-2xl border border-dashed border-slate-300 bg-white p-6 text-center text-sm text-slate-500">
                <p>Contact information is not available yet. Use the button below to add the bureau details.</p>
                <div class="mt-4 flex justify-center">
                    <a
                        href="{{ route('admin.contact-info.create') }}"
                        class="btn-primary"
                    >
                        Add contact info
                    </a>
                </div>
            </div>
        @endif
    </div>
@endsection



