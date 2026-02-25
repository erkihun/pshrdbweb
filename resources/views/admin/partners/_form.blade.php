@php
    $partner ??= null;
    $partnerTypes = [
        'government' => __('partners.types.government'),
        'ngo' => __('partners.types.ngo'),
        'private' => __('partners.types.private'),
        'international' => __('partners.types.international'),
        'other' => __('partners.types.other'),
    ];
@endphp

<div class="space-y-6">
    <div class="grid gap-6 md:grid-cols-2">
        <div>
            <label for="name_am" class="block text-sm font-semibold text-slate-700">{{ __('partners.form.name_am') }}</label>
            <input
                id="name_am"
                name="name_am"
                type="text"
                value="{{ old('name_am', $partner->name_am ?? '') }}"
                class="form-input"
                required
            >
            @error('name_am')
                <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="name_en" class="block text-sm font-semibold text-slate-700">{{ __('partners.form.name_en') }}</label>
            <input
                id="name_en"
                name="name_en"
                type="text"
                value="{{ old('name_en', $partner->name_en ?? '') }}"
                class="form-input"
            >
            @error('name_en')
                <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
            @enderror
        </div>
    </div>

    <div class="grid gap-6 md:grid-cols-2">
        <div>
            <label for="short_name" class="block text-sm font-semibold text-slate-700">{{ __('partners.form.short_name') }}</label>
            <input
                id="short_name"
                name="short_name"
                type="text"
                value="{{ old('short_name', $partner->short_name ?? '') }}"
                class="form-input"
            >
        </div>

        <div>
            <label for="type" class="block text-sm font-semibold text-slate-700">{{ __('partners.form.type') }}</label>
            <select id="type" name="type" class="form-input">
                @foreach($partnerTypes as $value => $label)
                    <option value="{{ $value }}" @selected(old('type', $partner->type ?? 'other') === $value)>{{ $label }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="grid gap-6 md:grid-cols-2">
        <div>
            <label for="country" class="block text-sm font-semibold text-slate-700">{{ __('partners.form.country') }}</label>
            <input
                id="country"
                name="country"
                type="text"
                value="{{ old('country', $partner->country ?? 'Ethiopia') }}"
                class="form-input"
            >
        </div>
        <div>
            <label for="city" class="block text-sm font-semibold text-slate-700">{{ __('partners.form.city') }}</label>
            <input
                id="city"
                name="city"
                type="text"
                value="{{ old('city', $partner->city ?? '') }}"
                class="form-input"
            >
        </div>
    </div>

    <div class="grid gap-6 md:grid-cols-2">
        <div>
            <label for="website_url" class="block text-sm font-semibold text-slate-700">{{ __('partners.form.website') }}</label>
            <input
                id="website_url"
                name="website_url"
                type="url"
                value="{{ old('website_url', $partner->website_url ?? '') }}"
                class="form-input"
            >
        </div>
        <div>
            <label for="phone" class="block text-sm font-semibold text-slate-700">{{ __('partners.form.phone') }}</label>
            <input
                id="phone"
                name="phone"
                type="text"
                value="{{ old('phone', $partner->phone ?? '') }}"
                class="form-input"
            >
        </div>
    </div>

    <div class="grid gap-6 md:grid-cols-2">
        <div>
            <label for="email" class="block text-sm font-semibold text-slate-700">{{ __('partners.form.email') }}</label>
            <input
                id="email"
                name="email"
                type="email"
                value="{{ old('email', $partner->email ?? '') }}"
                class="form-input"
            >
        </div>
        <div>
            <label for="logo" class="block text-sm font-semibold text-slate-700">{{ __('partners.form.logo') }}</label>
            <input id="logo" name="logo" type="file" accept="image/*" class="form-input">
            @if(isset($partner) && $partner->logo_path)
                <p class="text-xs text-slate-500 mt-2">{{ __('partners.form.current_logo', ['filename' => basename($partner->logo_path)]) }}</p>
            @endif
            @error('logo')
                <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
            @enderror
        </div>
    </div>

    <div>
        <label for="address" class="block text-sm font-semibold text-slate-700">{{ __('partners.form.address') }}</label>
        <textarea id="address" name="address" rows="3" class="form-textarea">{{ old('address', $partner->address ?? '') }}</textarea>
    </div>

    <div class="flex items-center gap-3">
        <input type="hidden" name="is_active" value="0">
        <label class="flex items-center gap-2 text-sm font-semibold text-slate-700">
            <input
                type="checkbox"
                name="is_active"
                value="1"
                @checked(old('is_active', $partner->is_active ?? true))
            >
            {{ __('partners.statuses.active') }}
        </label>
    </div>
</div>
