@extends('admin.layouts.app')

@section('content')
    @php
        use Illuminate\Support\Facades\Storage;
    @endphp

    <form method="POST" action="{{ route('admin.settings.update') }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="space-y-6">
            <section class="rounded-lg bg-white p-6 shadow">
                <div class="flex items-center justify-between">
                    <h2 class="text-lg font-semibold text-slate-700">{{ __('common.settings.branding') }}</h2>
                    <div class="text-xs uppercase tracking-wide text-slate-500">{{ __('common.settings.branding_hint') }}</div>
                </div>
                <div class="mt-4 grid gap-4 md:grid-cols-2">
                    <div>
                        <label class="text-sm font-semibold text-slate-600" for="site_name_am">{{ __('common.labels.title') }} (AM)</label>
                        <input
                            type="text"
                            id="site_name_am"
                            name="site_name_am"
                            value="{{ old('site_name_am', $branding['site_name_am'] ?? '') }}"
                            class="mt-1 w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-2 text-sm text-slate-700 focus:border-blue-500 focus:bg-white focus:outline-none"
                        >
                        @error('site_name_am')
                            <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="text-sm font-semibold text-slate-600" for="site_name_en">{{ __('common.labels.title') }} (EN)</label>
                        <input
                            type="text"
                            id="site_name_en"
                            name="site_name_en"
                            value="{{ old('site_name_en', $branding['site_name_en'] ?? '') }}"
                            class="mt-1 w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-2 text-sm text-slate-700 focus:border-blue-500 focus:bg-white focus:outline-none"
                        >
                        @error('site_name_en')
                            <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="mt-4 grid gap-4 md:grid-cols-2">
                    <div>
                        <label class="text-sm font-semibold text-slate-600" for="logo">{{ __('common.labels.logo') }}</label>
                        <input
                            type="file"
                            name="logo"
                            id="logo"
                            class="mt-1 w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm text-slate-600"
                            accept="image/*"
                        >
                        @if(!empty($branding['logo_path']))
                            <div class="mt-2 flex items-center gap-4">
                                <img src="{{ Storage::disk('public')->url($branding['logo_path']) }}" alt="Logo" class="h-12 w-auto rounded border border-slate-200">
                                <span class="text-xs text-slate-500">{{ __('common.settings.current_logo') }}</span>
                            </div>
                        @endif
                        @error('logo')
                            <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="text-sm font-semibold text-slate-600" for="favicon">{{ __('common.settings.favicon') }}</label>
                        <input
                            type="file"
                            name="favicon"
                            id="favicon"
                            class="mt-1 w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm text-slate-600"
                            accept="image/*"
                        >
                        @if(!empty($branding['favicon_path']))
                            <div class="mt-2 flex items-center gap-4">
                                <span class="inline-flex h-10 w-10 items-center justify-center rounded border border-slate-200 bg-white text-xs text-slate-500">
                                    favicon
                                </span>
                                <span class="text-xs text-slate-500">{{ __('common.settings.current_favicon') }}</span>
                            </div>
                        @endif
                        @error('favicon')
                            <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </section>

            <section class="rounded-lg bg-white p-6 shadow">
                <div class="flex items-center justify-between">
                    <h2 class="text-lg font-semibold text-slate-700">{{ __('common.settings.contact') }}</h2>
                </div>

                <div class="mt-4 grid gap-4 md:grid-cols-2">
                    <div>
                        <label class="text-sm font-semibold text-slate-600" for="address_am">{{ __('common.labels.address_am') }}</label>
                        <input
                            type="text"
                            id="address_am"
                            name="address_am"
                            value="{{ old('address_am', $contact['address_am'] ?? '') }}"
                            class="mt-1 w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-2 text-sm text-slate-700 focus:border-blue-500 focus:bg-white focus:outline-none"
                        >
                    </div>
                    <div>
                        <label class="text-sm font-semibold text-slate-600" for="address_en">{{ __('common.labels.address_en') }}</label>
                        <input
                            type="text"
                            id="address_en"
                            name="address_en"
                            value="{{ old('address_en', $contact['address_en'] ?? '') }}"
                            class="mt-1 w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-2 text-sm text-slate-700 focus:border-blue-500 focus:bg-white focus:outline-none"
                        >
                    </div>
                </div>

                <div class="mt-4 grid gap-4 md:grid-cols-3">
                    <div>
                        <label class="text-sm font-semibold text-slate-600" for="phone">{{ __('common.labels.phone') }}</label>
                        <input
                            type="text"
                            id="phone"
                            name="phone"
                            value="{{ old('phone', $contact['phone'] ?? '') }}"
                            class="mt-1 w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-2 text-sm text-slate-700 focus:border-blue-500 focus:bg-white focus:outline-none"
                        >
                    </div>
                    <div>
                        <label class="text-sm font-semibold text-slate-600" for="email">{{ __('common.labels.email') }}</label>
                        <input
                            type="email"
                            id="email"
                            name="email"
                            value="{{ old('email', $contact['email'] ?? '') }}"
                            class="mt-1 w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-2 text-sm text-slate-700 focus:border-blue-500 focus:bg-white focus:outline-none"
                        >
                    </div>
                    <div>
                        <label class="text-sm font-semibold text-slate-600" for="working_hours_en">{{ __('common.gov.office_hours') }} (EN)</label>
                        <input
                            type="text"
                            id="working_hours_en"
                            name="working_hours_en"
                            value="{{ old('working_hours_en', $contact['working_hours_en'] ?? '') }}"
                            class="mt-1 w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-2 text-sm text-slate-700 focus:border-blue-500 focus:bg-white focus:outline-none"
                        >
                    </div>
                </div>

                <div class="mt-4 grid gap-4 md:grid-cols-1">
                    <div>
                        <label class="text-sm font-semibold text-slate-600" for="working_hours_am">{{ __('common.gov.office_hours') }} (AM)</label>
                        <input
                            type="text"
                            id="working_hours_am"
                            name="working_hours_am"
                            value="{{ old('working_hours_am', $contact['working_hours_am'] ?? '') }}"
                            class="mt-1 w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-2 text-sm text-slate-700 focus:border-blue-500 focus:bg-white focus:outline-none"
                        >
                    </div>
                </div>
            </section>

            <section class="rounded-lg bg-white p-6 shadow">
                <div class="flex items-center justify-between">
                    <h2 class="text-lg font-semibold text-slate-700">{{ __('common.settings.notifications') }}</h2>
                </div>

                <div class="mt-4 grid gap-4 md:grid-cols-2">
                    <div>
                        <label class="text-sm font-semibold text-slate-600" for="admin_email">{{ __('common.labels.email') }} (Admin)</label>
                        <input
                            type="email"
                            id="admin_email"
                            name="admin_email"
                            value="{{ old('admin_email', $notifications['admin_email'] ?? '') }}"
                            class="mt-1 w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-2 text-sm text-slate-700 focus:border-blue-500 focus:bg-white focus:outline-none"
                        >
                    </div>
                    <div class="flex items-center gap-4">
                        <label class="text-sm font-semibold text-slate-600" for="enable_email">{{ __('common.settings.enable_email') }}</label>
                        <input
                            type="checkbox"
                            id="enable_email"
                            name="enable_email"
                            value="1"
                            {{ old('enable_email', $notifications['enable_email'] ?? false) ? 'checked' : '' }}
                            class="h-4 w-4 rounded border-slate-300 text-blue-500 focus:ring-blue-500"
                        >
                    </div>
                    <div class="flex items-center gap-4">
                        <label class="text-sm font-semibold text-slate-600" for="enable_sms">{{ __('common.settings.enable_sms') }}</label>
                        <input
                            type="checkbox"
                            id="enable_sms"
                            name="enable_sms"
                            value="1"
                            {{ old('enable_sms', $notifications['enable_sms'] ?? false) ? 'checked' : '' }}
                            class="h-4 w-4 rounded border-slate-300 text-blue-500 focus:ring-blue-500"
                        >
                    </div>
                </div>
            </section>

            <section class="rounded-lg bg-white p-6 shadow">
                <div class="flex items-center justify-between">
                    <h2 class="text-lg font-semibold text-slate-700">{{ __('common.settings.analytics') }}</h2>
                </div>
                <div class="mt-4 flex items-center gap-4">
                    <label class="text-sm font-semibold text-slate-600" for="analytics_enabled">{{ __('common.settings.enable_analytics') }}</label>
                    <input
                        type="checkbox"
                        id="analytics_enabled"
                        name="analytics_enabled"
                        value="1"
                        {{ old('analytics_enabled', $analytics['enabled'] ?? false) ? 'checked' : '' }}
                        class="h-4 w-4 rounded border-slate-300 text-blue-500 focus:ring-blue-500"
                    >
                </div>
            </section>

            <section class="rounded-lg bg-white p-6 shadow">
                <div class="flex items-center justify-between">
                    <h2 class="text-lg font-semibold text-slate-700">{{ __('common.settings.footer_quick_links') }}</h2>
                    <div class="text-xs uppercase tracking-wide text-slate-500">{{ __('common.settings.footer_hint') }}</div>
                </div>

                @php
                    $footerLinks = $footer['quick_links'] ?? [];
                    $footerLinksCount = max(3, count($footerLinks));
                @endphp

                @for ($i = 0; $i < $footerLinksCount; $i++)
                    @php
                        $link = $footerLinks[$i] ?? ['label_am' => '', 'label_en' => '', 'url' => ''];
                    @endphp
                    <div class="mt-4 rounded-lg border border-slate-200 bg-slate-50 p-4">
                        <div class="grid gap-4 md:grid-cols-3">
                            <div>
                                <label class="text-xs font-semibold uppercase tracking-wide text-slate-500">AM {{ __('common.labels.title') }}</label>
                                <input
                                    type="text"
                                    name="quick_links[{{ $i }}][label_am]"
                                    value='{{ old("quick_links.{$i}.label_am", $link['label_am'] ?? '') }}'
                                    class="mt-1 w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm text-slate-700"
                                >
                            </div>
                            <div>
                                <label class="text-xs font-semibold uppercase tracking-wide text-slate-500">EN {{ __('common.labels.title') }}</label>
                                <input
                                    type="text"
                                    name="quick_links[{{ $i }}][label_en]"
                                    value='{{ old("quick_links.{$i}.label_en", $link['label_en'] ?? '') }}'
                                    class="mt-1 w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm text-slate-700"
                                >
                            </div>
                            <div>
                                <label class="text-xs font-semibold uppercase tracking-wide text-slate-500">URL</label>
                                <input
                                    type="text"
                                    name="quick_links[{{ $i }}][url]"
                                    value='{{ old("quick_links.{$i}.url", $link['url'] ?? '') }}'
                                    class="mt-1 w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm text-slate-700"
                                >
                            </div>
                        </div>
                    </div>
                @endfor
            </section>

            <section class="rounded-lg bg-white p-6 shadow">
                <div class="flex items-center justify-between">
                    <h2 class="text-lg font-semibold text-slate-700">{{ __('common.settings.social_links') }}</h2>
                </div>

                @php
                    $socialLinks = $footer['social_links'] ?? [];
                    $socialLinksCount = max(3, count($socialLinks));
                @endphp

                @for ($i = 0; $i < $socialLinksCount; $i++)
                    @php
                        $link = $socialLinks[$i] ?? ['label_am' => '', 'label_en' => '', 'url' => ''];
                    @endphp
                    <div class="mt-4 rounded-lg border border-slate-200 bg-slate-50 p-4">
                        <div class="grid gap-4 md:grid-cols-3">
                            <div>
                                <label class="text-xs font-semibold uppercase tracking-wide text-slate-500">AM {{ __('common.labels.title') }}</label>
                                <input
                                    type="text"
                                    name="social_links[{{ $i }}][label_am]"
                                    value="{{ old('social_links.' . $i . '.label_am', $link['label_am'] ?? '') }}"
                                    class="mt-1 w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm text-slate-700"
                                >
                            </div>
                            <div>
                                <label class="text-xs font-semibold uppercase tracking-wide text-slate-500">EN {{ __('common.labels.title') }}</label>
                                <input
                                    type="text"
                                    name="social_links[{{ $i }}][label_en]"
                                    value="{{ old('social_links.' . $i . '.label_en', $link['label_en'] ?? '') }}"
                                    class="mt-1 w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm text-slate-700"
                                >
                            </div>
                            <div>
                                <label class="text-xs font-semibold uppercase tracking-wide text-slate-500">URL</label>
                                <input
                                    type="text"
                                    name="social_links[{{ $i }}][url]"
                                    value="{{ old('social_links.' . $i . '.url', $link['url'] ?? '') }}"
                                    class="mt-1 w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm text-slate-700"
                                >
                            </div>
                        </div>
                    </div>
                @endfor
            </section>

            <div class="rounded-lg bg-white p-6 shadow">
                <button type="submit" class="inline-flex items-center justify-center rounded-lg bg-blue-600 px-5 py-2 text-sm font-semibold uppercase tracking-wide text-white transition hover:bg-blue-700">
                    {{ __('common.actions.save') }}
                </button>
            </div>
        </div>
    </form>
@endsection

