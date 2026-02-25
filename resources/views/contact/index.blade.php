<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-900 leading-tight">
            {{ __('common.nav.contact') }}
        </h2>
    </x-slot>


    <div class="py-12">
        <div class="mx-auto max-w-6xl space-y-8 px-4 sm:px-6 lg:px-8">
            @if($contactInfo)
                <div class="grid gap-6 lg:grid-cols-[1.05fr_0.95fr]">
                    <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-xs uppercase tracking-wide text-slate-500">Bureau identity</p>
                                <h3 class="text-2xl font-semibold text-slate-900">{{ $contactInfo->bureau_name }}</h3>
                            </div>
                            @if($contactInfo->website_url)
                                <a
                                    href="{{ $contactInfo->website_url }}"
                                    target="_blank"
                                    rel="nofollow noopener"
                                    class="text-sm font-semibold text-blue-600 hover:underline"
                                >
                                    Visit website
                                </a>
                            @endif
                        </div>


                        <div class="mt-6 grid gap-6 md:grid-cols-2">
                            <div>
                                <p class="text-sm font-semibold text-slate-500">Address</p>
                                <p class="mt-2 text-sm text-slate-700 whitespace-pre-line">
                                    {{ $contactInfo->physical_address }}<br>
                                    {{ $contactInfo->city }}{{ $contactInfo->region ? ', '.$contactInfo->region : '' }}<br>
                                    {{ $contactInfo->country }}{{ $contactInfo->postal_code ? ' A� '.$contactInfo->postal_code : '' }}
                                </p>
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-slate-500">Office hours</p>
                                <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">Schedules &amp; brief</p>
                                <div class="mt-2 space-y-2 text-sm text-slate-700">
                                    {!! $contactInfo->sanitized_office_hours ?: '<p>Weekdays A� 08:00 �?" 17:00</p>' !!}
                                </div>
                            </div>
                        </div>
                        <div class="mt-6 grid gap-6 md:grid-cols-2">
                            <div>
                                <p class="text-sm font-semibold text-slate-500">Phones</p>
                                @if($contactInfo->phone_primary)
                                    <a href="tel:{{ preg_replace('/\\D+/', '', $contactInfo->phone_primary) }}" class="mt-2 block text-sm font-medium text-slate-900 hover:text-blue-600">
                                        {{ $contactInfo->phone_primary }}
                                    </a>
                                @endif
                                @if($contactInfo->phone_secondary)
                                    <a href="tel:{{ preg_replace('/\\D+/', '', $contactInfo->phone_secondary) }}" class="block text-sm font-medium text-slate-900 hover:text-blue-600">
                                        {{ $contactInfo->phone_secondary }}
                                    </a>
                                @endif
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-slate-500">Email</p>
                                @if($contactInfo->email_primary)
                                    <a href="mailto:{{ $contactInfo->email_primary }}" class="mt-2 block text-sm font-medium text-slate-900 hover:text-blue-600">
                                        {{ $contactInfo->email_primary }}
                                    </a>
                                @endif
                                @if($contactInfo->email_secondary)
                                    <a href="mailto:{{ $contactInfo->email_secondary }}" class="block text-sm font-medium text-slate-900 hover:text-blue-600">
                                        {{ $contactInfo->email_secondary }}
                                    </a>
                                @endif
                            </div>
                        </div>


                        <div class="mt-6 space-y-2">
                            <p class="text-sm font-semibold text-slate-500">Connect with us</p>
                            <div class="flex flex-wrap gap-3 text-sm">
                                @if($contactInfo->facebook_url)
                                    <a href="{{ $contactInfo->facebook_url }}" target="_blank" rel="nofollow noopener" class="text-blue-600 hover:underline">Facebook</a>
                                @endif
                                @if($contactInfo->telegram_url)
                                    <a href="{{ $contactInfo->telegram_url }}" target="_blank" rel="nofollow noopener" class="text-blue-600 hover:underline">Telegram</a>
                                @endif
                                @if($contactInfo->x_url)
                                    <a href="{{ $contactInfo->x_url }}" target="_blank" rel="nofollow noopener" class="text-blue-600 hover:underline">X</a>
                                @endif
                                @if($contactInfo->linkedin_url)
                                    <a href="{{ $contactInfo->linkedin_url }}" target="_blank" rel="nofollow noopener" class="text-blue-600 hover:underline">LinkedIn</a>
                                @endif
                            </div>
                        </div>
                    </div>


                    <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-xs uppercase tracking-wide text-slate-500">Location map</p>
                                <h3 class="text-lg font-semibold text-slate-900">Find us here</h3>
                            </div>
                        </div>
                        <div class="mt-5 h-64 w-full overflow-hidden rounded-xl border border-gray-200">
                            @if($contactInfo->map_iframe_html)
                                <div class="h-full w-full">
                                    {!! $contactInfo->map_iframe_html !!}
                                </div>
                            @elseif($contactInfo->map_embed_url)
                                <iframe
                                    class="h-full w-full border-0"
                                    loading="lazy"
                                    src="{{ $contactInfo->map_embed_url }}"
                                    title="Bureau location map"
                                    allowfullscreen
                                ></iframe>
                            @else
                                <div class="flex h-full items-center justify-center text-sm text-slate-500">
                                    Map preview not available.
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @else
                <div class="rounded-2xl border border-dashed border-gray-300 bg-white p-6 text-center text-sm text-slate-500">
                    Contact information is being prepared. Please check back soon.
                </div>
            @endif


            <div class="rounded-2xl border border-gray-200 bg-white p-8 shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs uppercase tracking-wide text-slate-500">Message the team</p>
                        <h3 class="text-xl font-semibold text-slate-900">Send a note</h3>
                    </div>
                </div>
                @if (session('success'))
                    <div class="mt-4 rounded-lg bg-green-50 p-4 text-sm text-green-800">
                        {{ session('success') }}
                    </div>
                @endif
                <form method="POST" action="{{ route('contact.store') }}" class="mt-6 space-y-6">
                    @csrf


                    <div>
                        <label class="text-sm font-semibold text-gray-700" for="name">{{ __('common.labels.full_name') }}</label>
                        <input
                            id="name"
                            name="name"
                            type="text"
                            value="{{ old('name') }}"
                            class="form-input"
                            required
                        >
                        @error('name')
                            <p class="mt-2 text-xs text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>


                    <div>
                        <label class="text-sm font-semibold text-gray-700" for="email">{{ __('common.labels.email') }}</label>
                        <input
                            id="email"
                            name="email"
                            type="email"
                            value="{{ old('email') }}"
                            class="form-input"
                            required
                        >
                        @error('email')
                            <p class="mt-2 text-xs text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>


                    <div>
                        <label class="text-sm font-semibold text-gray-700" for="subject">{{ __('common.labels.subject') }}</label>
                        <input
                            id="subject"
                            name="subject"
                            type="text"
                            value="{{ old('subject') }}"
                            class="form-input"
                            required
                        >
                        @error('subject')
                            <p class="mt-2 text-xs text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>


                    <div>
                        <label class="text-sm font-semibold text-gray-700" for="message">{{ __('common.labels.message') }}</label>
                        <textarea
                            id="message"
                            name="message"
                            rows="6"
                            class="form-textarea"
                            required
                        >{{ old('message') }}</textarea>
                        @error('message')
                            <p class="mt-2 text-xs text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>


                    <div class="flex justify-end">
                        <button
                            type="submit"
                            class="btn-primary"
                        >
                            {{ __('common.actions.submit') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>









