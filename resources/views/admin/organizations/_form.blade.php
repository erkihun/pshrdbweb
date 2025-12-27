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
</div>
