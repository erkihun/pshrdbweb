@php
    $mapIframe = old('map_iframe_html', $contactInfo->map_iframe_html ?? '');
    $mapEmbed = old('map_embed_url', $contactInfo->map_embed_url ?? '');
@endphp

<div class="space-y-6">
    <section class="space-y-4 rounded-2xl border border-slate-200 bg-white p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs uppercase tracking-wide text-slate-400">Bureau identity</p>
                <h2 class="text-lg font-semibold text-slate-900">Overview</h2>
            </div>
        </div>
        <div class="grid gap-4 lg:grid-cols-2">
            <div>
                <label class="text-xs font-semibold uppercase tracking-wide text-slate-500" for="bureau_name">Bureau name</label>
                <input
                    id="bureau_name"
                    name="bureau_name"
                    type="text"
                    value="{{ old('bureau_name', $contactInfo->bureau_name ?? '') }}"
                    class="mt-1 w-full rounded-lg border border-slate-200 px-3 py-2 text-sm focus:border-blue-500 focus:ring-blue-500"
                    required
                >
                @error('bureau_name')
                    <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label class="text-xs font-semibold uppercase tracking-wide text-slate-500" for="website_url">Website URL</label>
                <input
                    id="website_url"
                    name="website_url"
                    type="url"
                    value="{{ old('website_url', $contactInfo->website_url ?? '') }}"
                    class="mt-1 w-full rounded-lg border border-slate-200 px-3 py-2 text-sm focus:border-blue-500 focus:ring-blue-500"
                >
                @error('website_url')
                    <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                @enderror
            </div>
        </div>
    </section>

    <section class="space-y-4 rounded-2xl border border-slate-200 bg-white p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs uppercase tracking-wide text-slate-400">Address</p>
                <h2 class="text-lg font-semibold text-slate-900">Location</h2>
            </div>
        </div>
        <div class="grid gap-4 lg:grid-cols-2">
            <div>
                <label class="text-xs font-semibold uppercase tracking-wide text-slate-500" for="physical_address">Physical address</label>
                <input
                    id="physical_address"
                    name="physical_address"
                    type="text"
                    value="{{ old('physical_address', $contactInfo->physical_address ?? '') }}"
                    class="mt-1 w-full rounded-lg border border-slate-200 px-3 py-2 text-sm focus:border-blue-500 focus:ring-blue-500"
                    required
                >
                @error('physical_address')
                    <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label class="text-xs font-semibold uppercase tracking-wide text-slate-500" for="city">City</label>
                <input
                    id="city"
                    name="city"
                    type="text"
                    value="{{ old('city', $contactInfo->city ?? 'Addis Ababa') }}"
                    class="mt-1 w-full rounded-lg border border-slate-200 px-3 py-2 text-sm focus:border-blue-500 focus:ring-blue-500"
                    required
                >
                @error('city')
                    <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                @enderror
            </div>
        </div>
        <div class="grid gap-4 lg:grid-cols-3">
            <div>
                <label class="text-xs font-semibold uppercase tracking-wide text-slate-500" for="region">Region</label>
                <input
                    id="region"
                    name="region"
                    type="text"
                    value="{{ old('region', $contactInfo->region ?? '') }}"
                    class="mt-1 w-full rounded-lg border border-slate-200 px-3 py-2 text-sm focus:border-blue-500 focus:ring-blue-500"
                >
                @error('region')
                    <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label class="text-xs font-semibold uppercase tracking-wide text-slate-500" for="country">Country</label>
                <input
                    id="country"
                    name="country"
                    type="text"
                    value="{{ old('country', $contactInfo->country ?? 'Ethiopia') }}"
                    class="mt-1 w-full rounded-lg border border-slate-200 px-3 py-2 text-sm focus:border-blue-500 focus:ring-blue-500"
                    required
                >
                @error('country')
                    <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label class="text-xs font-semibold uppercase tracking-wide text-slate-500" for="postal_code">Postal code</label>
                <input
                    id="postal_code"
                    name="postal_code"
                    type="text"
                    value="{{ old('postal_code', $contactInfo->postal_code ?? '') }}"
                    class="mt-1 w-full rounded-lg border border-slate-200 px-3 py-2 text-sm focus:border-blue-500 focus:ring-blue-500"
                >
                @error('postal_code')
                    <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                @enderror
            </div>
        </div>
    </section>

    <section class="space-y-4 rounded-2xl border border-slate-200 bg-white p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs uppercase tracking-wide text-slate-400">Contact</p>
                <h2 class="text-lg font-semibold text-slate-900">Direct lines</h2>
            </div>
        </div>
        <div class="grid gap-4 lg:grid-cols-2">
            <div>
                <label class="text-xs font-semibold uppercase tracking-wide text-slate-500" for="phone_primary">Primary phone</label>
                <input
                    id="phone_primary"
                    name="phone_primary"
                    type="text"
                    value="{{ old('phone_primary', $contactInfo->phone_primary ?? '') }}"
                    class="mt-1 w-full rounded-lg border border-slate-200 px-3 py-2 text-sm focus:border-blue-500 focus:ring-blue-500"
                >
                @error('phone_primary')
                    <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label class="text-xs font-semibold uppercase tracking-wide text-slate-500" for="phone_secondary">Secondary phone</label>
                <input
                    id="phone_secondary"
                    name="phone_secondary"
                    type="text"
                    value="{{ old('phone_secondary', $contactInfo->phone_secondary ?? '') }}"
                    class="mt-1 w-full rounded-lg border border-slate-200 px-3 py-2 text-sm focus:border-blue-500 focus:ring-blue-500"
                >
                @error('phone_secondary')
                    <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                @enderror
            </div>
        </div>
        <div class="grid gap-4 lg:grid-cols-2">
            <div>
                <label class="text-xs font-semibold uppercase tracking-wide text-slate-500" for="email_primary">Primary email</label>
                <input
                    id="email_primary"
                    name="email_primary"
                    type="email"
                    value="{{ old('email_primary', $contactInfo->email_primary ?? '') }}"
                    class="mt-1 w-full rounded-lg border border-slate-200 px-3 py-2 text-sm focus:border-blue-500 focus:ring-blue-500"
                >
                @error('email_primary')
                    <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label class="text-xs font-semibold uppercase tracking-wide text-slate-500" for="email_secondary">Secondary email</label>
                <input
                    id="email_secondary"
                    name="email_secondary"
                    type="email"
                    value="{{ old('email_secondary', $contactInfo->email_secondary ?? '') }}"
                    class="mt-1 w-full rounded-lg border border-slate-200 px-3 py-2 text-sm focus:border-blue-500 focus:ring-blue-500"
                >
                @error('email_secondary')
                    <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                @enderror
            </div>
        </div>
    </section>

    <section class="space-y-4 rounded-2xl border border-slate-200 bg-white p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs uppercase tracking-wide text-slate-400">Office hours</p>
                <h2 class="text-lg font-semibold text-slate-900">Schedules & brief</h2>
            </div>
        </div>
        <textarea
            name="office_hours"
            rows="4"
            data-editor="tinymce"
            class="mt-1 w-full rounded-lg border border-slate-200 px-3 py-2 text-sm focus:border-blue-500 focus:ring-blue-500"
        >{{ old('office_hours', $contactInfo->office_hours ?? '') }}</textarea>
        @error('office_hours')
            <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
        @enderror
    </section>

    <section class="space-y-4 rounded-2xl border border-slate-200 bg-white p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs uppercase tracking-wide text-slate-400">Location map</p>
                <h2 class="text-lg font-semibold text-slate-900">Embed</h2>
            </div>
        </div>
        <div class="space-y-4">
            <div>
                <label class="text-xs font-semibold uppercase tracking-wide text-slate-500" for="map_embed_url">Map embed URL</label>
                <input
                    id="map_embed_url"
                    name="map_embed_url"
                    type="url"
                    value="{{ $mapEmbed }}"
                    class="mt-1 w-full rounded-lg border border-slate-200 px-3 py-2 text-sm focus:border-blue-500 focus:ring-blue-500"
                >
                @error('map_embed_url')
                    <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label class="text-xs font-semibold uppercase tracking-wide text-slate-500" for="map_iframe_html">Map iframe HTML</label>
                <textarea
                    id="map_iframe_html"
                    name="map_iframe_html"
                    rows="4"
                    class="mt-1 w-full rounded-lg border border-slate-200 px-3 py-2 text-sm focus:border-blue-500 focus:ring-blue-500"
                >{{ $mapIframe }}</textarea>
                @error('map_iframe_html')
                    <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                @enderror
            </div>
            @if($mapIframe || $mapEmbed)
                <div class="rounded-2xl border border-slate-100 bg-slate-50 p-4">
                    <p class="text-xs font-semibold text-slate-500">Preview</p>
                    <div class="mt-2 h-48 overflow-hidden rounded-xl border border-slate-200">
                        @if($mapIframe)
                            {!! $mapIframe !!}
                        @else
                            <iframe class="h-full w-full border-0" src="{{ $mapEmbed }}" loading="lazy"></iframe>
                        @endif
                    </div>
                </div>
            @endif
        </div>
    </section>

    <section class="space-y-4 rounded-2xl border border-slate-200 bg-white p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs uppercase tracking-wide text-slate-400">Social</p>
                <h2 class="text-lg font-semibold text-slate-900">Profiles</h2>
            </div>
        </div>
        <div class="grid gap-4 md:grid-cols-2">
            <div>
                <label class="text-xs font-semibold uppercase tracking-wide text-slate-500" for="facebook_url">Facebook</label>
                <input
                    id="facebook_url"
                    name="facebook_url"
                    type="url"
                    value="{{ old('facebook_url', $contactInfo->facebook_url ?? '') }}"
                    class="mt-1 w-full rounded-lg border border-slate-200 px-3 py-2 text-sm focus:border-blue-500 focus:ring-blue-500"
                >
                @error('facebook_url')
                    <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label class="text-xs font-semibold uppercase tracking-wide text-slate-500" for="telegram_url">Telegram</label>
                <input
                    id="telegram_url"
                    name="telegram_url"
                    type="url"
                    value="{{ old('telegram_url', $contactInfo->telegram_url ?? '') }}"
                    class="mt-1 w-full rounded-lg border border-slate-200 px-3 py-2 text-sm focus:border-blue-500 focus:ring-blue-500"
                >
                @error('telegram_url')
                    <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label class="text-xs font-semibold uppercase tracking-wide text-slate-500" for="x_url">X</label>
                <input
                    id="x_url"
                    name="x_url"
                    type="url"
                    value="{{ old('x_url', $contactInfo->x_url ?? '') }}"
                    class="mt-1 w-full rounded-lg border border-slate-200 px-3 py-2 text-sm focus:border-blue-500 focus:ring-blue-500"
                >
                @error('x_url')
                    <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label class="text-xs font-semibold uppercase tracking-wide text-slate-500" for="linkedin_url">LinkedIn</label>
                <input
                    id="linkedin_url"
                    name="linkedin_url"
                    type="url"
                    value="{{ old('linkedin_url', $contactInfo->linkedin_url ?? '') }}"
                    class="mt-1 w-full rounded-lg border border-slate-200 px-3 py-2 text-sm focus:border-blue-500 focus:ring-blue-500"
                >
                @error('linkedin_url')
                    <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                @enderror
            </div>
        </div>
        <div class="grid gap-4 md:grid-cols-3">
            <div>
                <label class="text-xs font-semibold uppercase tracking-wide text-slate-500" for="latitude">Latitude</label>
                <input
                    id="latitude"
                    name="latitude"
                    type="text"
                    value="{{ old('latitude', $contactInfo->latitude ?? '') }}"
                    class="mt-1 w-full rounded-lg border border-slate-200 px-3 py-2 text-sm focus:border-blue-500 focus:ring-blue-500"
                >
                @error('latitude')
                    <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label class="text-xs font-semibold uppercase tracking-wide text-slate-500" for="longitude">Longitude</label>
                <input
                    id="longitude"
                    name="longitude"
                    type="text"
                    value="{{ old('longitude', $contactInfo->longitude ?? '') }}"
                    class="mt-1 w-full rounded-lg border border-slate-200 px-3 py-2 text-sm focus:border-blue-500 focus:ring-blue-500"
                >
                @error('longitude')
                    <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                @enderror
            </div>
            <div class="flex items-center space-x-2 pt-8">
                <input
                    id="is_active"
                    name="is_active"
                    type="checkbox"
                    value="1"
                    class="h-4 w-4 rounded border-slate-300 text-blue-600 focus:ring-blue-500"
                    {{ old('is_active', $contactInfo->is_active ?? true) ? 'checked' : '' }}
                >
                <label for="is_active" class="text-sm font-semibold text-slate-600">Keep record active</label>
            </div>
        </div>
    </section>

    <div class="flex items-center justify-end gap-3">
        <a href="{{ route('admin.contact-info.index') }}" class="text-sm font-semibold text-slate-500 transition hover:text-slate-900">
            Cancel
        </a>
        <button type="submit" class="btn-primary">
            Save contact info
        </button>
    </div>
</div>
