@php
    $org = $organization ?? null;
@endphp

<div class="space-y-5">
    <div>
        <label class="block text-sm font-medium text-slate-700">{{ __('common.admin_organizations.form.name') }}</label>
        <input
            type="text"
            name="name"
            value="{{ old('name', optional($org)->name ?? '') }}"
            class="mt-2 w-full rounded-lg border border-slate-200 bg-white px-4 py-2 text-sm focus:border-blue-500 focus:ring-blue-500"
            required
        >
        @error('name')<p class="mt-1 text-xs text-rose-500">{{ $message }}</p>@enderror
    </div>

    <div>
        <label class="block text-sm font-medium text-slate-700">{{ __('common.admin_organizations.form.code') }}</label>
        <input
            type="text"
            name="code"
            value="{{ old('code', optional($org)->code ?? '') }}"
            class="mt-2 w-full rounded-lg border border-slate-200 bg-white px-4 py-2 text-sm focus:border-blue-500 focus:ring-blue-500"
        >
        @error('code')<p class="mt-1 text-xs text-rose-500">{{ $message }}</p>@enderror
    </div>

    <div>
        <div class="flex items-center gap-3">
            <input type="hidden" name="is_active" value="0">
            <label class="inline-flex cursor-pointer items-center gap-2 text-sm font-medium text-slate-700">
                <input
                    type="checkbox"
                    name="is_active"
                    value="1"
                    @checked(old('is_active', optional($org)->is_active ?? true))
                    class="h-4 w-4 rounded border-slate-300 text-blue-600 focus:ring-blue-500"
                >
                {{ __('common.admin_organizations.form.active') }}
            </label>
        </div>
        @error('is_active')<p class="mt-1 text-xs text-rose-500">{{ $message }}</p>@enderror
    </div>

    <p class="text-xs text-slate-500">
        {{ __('common.admin_organizations.form.note') }}
    </p>

    <section class="space-y-4 rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs uppercase tracking-wide text-slate-400">{{ __('common.admin_organizations.contact.heading') }}</p>
                <h2 class="text-lg font-semibold text-slate-900">{{ __('common.admin_organizations.contact.subtitle') }}</h2>
            </div>
        </div>
        <div class="grid gap-4 md:grid-cols-2">
            <div>
                <label class="block text-sm font-medium text-slate-700">{{ __('common.admin_organizations.contact.phone_primary') }}</label>
                <input
                    type="text"
                    name="phone_primary"
                    value="{{ old('phone_primary', optional($org)->phone_primary ?? '') }}"
                    class="mt-2 w-full rounded-lg border border-slate-200 bg-white px-4 py-2 text-sm focus:border-blue-500 focus:ring-blue-500"
                >
                @error('phone_primary')<p class="mt-1 text-xs text-rose-500">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700">{{ __('common.admin_organizations.contact.phone_secondary') }}</label>
                <input
                    type="text"
                    name="phone_secondary"
                    value="{{ old('phone_secondary', optional($org)->phone_secondary ?? '') }}"
                    class="mt-2 w-full rounded-lg border border-slate-200 bg-white px-4 py-2 text-sm focus:border-blue-500 focus:ring-blue-500"
                >
                @error('phone_secondary')<p class="mt-1 text-xs text-rose-500">{{ $message }}</p>@enderror
            </div>
        </div>
        <div class="grid gap-4 md:grid-cols-2">
            <div>
                <label class="block text-sm font-medium text-slate-700">{{ __('common.admin_organizations.contact.email_primary') }}</label>
                <input
                    type="email"
                    name="email_primary"
                    value="{{ old('email_primary', optional($org)->email_primary ?? '') }}"
                    class="mt-2 w-full rounded-lg border border-slate-200 bg-white px-4 py-2 text-sm focus:border-blue-500 focus:ring-blue-500"
                >
                @error('email_primary')<p class="mt-1 text-xs text-rose-500">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700">{{ __('common.admin_organizations.contact.email_secondary') }}</label>
                <input
                    type="email"
                    name="email_secondary"
                    value="{{ old('email_secondary', optional($org)->email_secondary ?? '') }}"
                    class="mt-2 w-full rounded-lg border border-slate-200 bg-white px-4 py-2 text-sm focus:border-blue-500 focus:ring-blue-500"
                >
                @error('email_secondary')<p class="mt-1 text-xs text-rose-500">{{ $message }}</p>@enderror
            </div>
        </div>
        <div>
            <label class="block text-sm font-medium text-slate-700">{{ __('common.admin_organizations.contact.physical_address') }}</label>
            <textarea
                name="physical_address"
                rows="2"
                class="mt-2 w-full rounded-lg border border-slate-200 bg-white px-4 py-2 text-sm focus:border-blue-500 focus:ring-blue-500"
            >{{ old('physical_address', optional($org)->physical_address ?? '') }}</textarea>
            @error('physical_address')<p class="mt-1 text-xs text-rose-500">{{ $message }}</p>@enderror
        </div>
        <div class="grid gap-4 md:grid-cols-3">
            <div>
            <label class="block text-sm font-medium text-slate-700">{{ __('common.admin_organizations.contact.city') }}</label>
                <input
                    type="text"
                    name="city"
                    value="{{ old('city', optional($org)->city ?? 'Addis Ababa') }}"
                    class="mt-2 w-full rounded-lg border border-slate-200 bg-white px-4 py-2 text-sm focus:border-blue-500 focus:ring-blue-500"
                >
                @error('city')<p class="mt-1 text-xs text-rose-500">{{ $message }}</p>@enderror
            </div>
            <div>
            <label class="block text-sm font-medium text-slate-700">{{ __('common.admin_organizations.contact.region') }}</label>
                <input
                    type="text"
                    name="region"
                    value="{{ old('region', optional($org)->region ?? '') }}"
                    class="mt-2 w-full rounded-lg border border-slate-200 bg-white px-4 py-2 text-sm focus:border-blue-500 focus:ring-blue-500"
                >
                @error('region')<p class="mt-1 text-xs text-rose-500">{{ $message }}</p>@enderror
            </div>
            <div>
            <label class="block text-sm font-medium text-slate-700">{{ __('common.admin_organizations.contact.country') }}</label>
                <input
                    type="text"
                    name="country"
                    value="{{ old('country', optional($org)->country ?? 'Ethiopia') }}"
                    class="mt-2 w-full rounded-lg border border-slate-200 bg-white px-4 py-2 text-sm focus:border-blue-500 focus:ring-blue-500"
                >
                @error('country')<p class="mt-1 text-xs text-rose-500">{{ $message }}</p>@enderror
            </div>
        </div>
        <div class="grid gap-4 md:grid-cols-2">
            <div>
            <label class="block text-sm font-medium text-slate-700">{{ __('common.admin_organizations.contact.website_url') }}</label>
                <input
                    type="url"
                    name="website_url"
                    value="{{ old('website_url', optional($org)->website_url ?? '') }}"
                    class="mt-2 w-full rounded-lg border border-slate-200 bg-white px-4 py-2 text-sm focus:border-blue-500 focus:ring-blue-500"
                >
                @error('website_url')<p class="mt-1 text-xs text-rose-500">{{ $message }}</p>@enderror
            </div>
            <div>
            <label class="block text-sm font-medium text-slate-700">{{ __('common.admin_organizations.contact.map_embed_url') }}</label>
                <input
                    type="url"
                    name="map_embed_url"
                    value="{{ old('map_embed_url', optional($org)->map_embed_url ?? '') }}"
                    class="mt-2 w-full rounded-lg border border-slate-200 bg-white px-4 py-2 text-sm focus:border-blue-500 focus:ring-blue-500"
                >
                @error('map_embed_url')<p class="mt-1 text-xs text-rose-500">{{ $message }}</p>@enderror
            </div>
        </div>
        @if(old('map_embed_url', optional($org)->map_embed_url ?? false))
            <div class="rounded-2xl border border-slate-200 bg-slate-50 p-3 text-sm text-slate-600">
                <p class="text-xs uppercase tracking-wide text-slate-400">{{ __('common.admin_organizations.contact.preview') }}</p>
                <div class="mt-2 h-40 overflow-hidden rounded-lg border border-slate-200">
                    <iframe
                        src="{{ old('map_embed_url', optional($org)->map_embed_url ?? '') }}"
                        class="h-full w-full border-0"
                        loading="lazy"
                        allowfullscreen
                    ></iframe>
                </div>
            </div>
        @endif
    </section>
</div>
