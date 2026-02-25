@php
    $mou ??= null;
    $statusOptions = [
        'draft' => __('mous.statuses.draft'),
        'active' => __('mous.statuses.active'),
        'expired' => __('mous.statuses.expired'),
        'terminated' => __('mous.statuses.terminated'),
    ];
@endphp

<div class="space-y-6">
    <div>
        <label for="partner_id" class="block text-sm font-semibold text-slate-700">{{ __('mous.form.partner') }}</label>
        <select id="partner_id" name="partner_id" class="form-input" required>
            <option value="">{{ __('common.labels.choose') }} {{ __('mous.form.partner') }}</option>
            @foreach($partners as $partner)
                <option value="{{ $partner->id }}" @selected(old('partner_id', $mou->partner_id ?? '') === $partner->id)>
                    {{ $partner->display_name }}
                </option>
            @endforeach
        </select>
        @error('partner_id')
            <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
        @enderror
    </div>

    <div class="grid gap-6 md:grid-cols-2">
        <div>
            <label for="title_am" class="block text-sm font-semibold text-slate-700">{{ __('mous.form.title_am') }}</label>
            <input
                id="title_am"
                name="title_am"
                type="text"
                value="{{ old('title_am', $mou->title_am ?? '') }}"
                class="form-input"
                required
            >
            @error('title_am')
                <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
            @enderror
        </div>
        <div>
            <label for="title_en" class="block text-sm font-semibold text-slate-700">{{ __('mous.form.title_en') }}</label>
            <input
                id="title_en"
                name="title_en"
                type="text"
                value="{{ old('title_en', $mou->title_en ?? '') }}"
                class="form-input"
            >
        </div>
    </div>

    <div class="grid gap-6 md:grid-cols-3">
        <div>
            <label for="reference_no" class="block text-sm font-semibold text-slate-700">{{ __('mous.form.reference_no') }}</label>
            <input
                id="reference_no"
                name="reference_no"
                type="text"
                value="{{ old('reference_no', $mou->reference_no ?? '') }}"
                class="form-input"
            >
        </div>
        <div>
            <label for="signed_at" class="block text-sm font-semibold text-slate-700">{{ __('mous.form.signed_at') }}</label>
            <input id="signed_at" name="signed_at" type="date" value="{{ old('signed_at', optional($mou)->signed_at?->toDateString()) }}" class="form-input">
        </div>
        <div>
            <label for="status" class="block text-sm font-semibold text-slate-700">{{ __('mous.form.status') }}</label>
            <select id="status" name="status" class="form-input" required>
                @foreach($statusOptions as $value => $label)
                    <option value="{{ $value }}" @selected(old('status', $mou->status ?? 'draft') === $value)>{{ $label }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="grid gap-6 md:grid-cols-2">
        <div>
            <label for="effective_from" class="block text-sm font-semibold text-slate-700">{{ __('mous.form.effective_from') }}</label>
            <input id="effective_from" name="effective_from" type="date" value="{{ old('effective_from', optional($mou)->effective_from?->toDateString()) }}" class="form-input">
        </div>
        <div>
            <label for="effective_to" class="block text-sm font-semibold text-slate-700">{{ __('mous.form.effective_to') }}</label>
            <input id="effective_to" name="effective_to" type="date" value="{{ old('effective_to', optional($mou)->effective_to?->toDateString()) }}" class="form-input">
        </div>
    </div>

    <div>
        <label for="scope_am" class="block text-sm font-semibold text-slate-700">{{ __('mous.form.scope_am') }}</label>
        <textarea
            id="scope_am"
            name="scope_am"
            rows="4"
            data-editor="tinymce"
            class="form-textarea"
        >{{ old('scope_am', $mou->scope_am ?? '') }}</textarea>
    </div>

    <div>
        <label for="scope_en" class="block text-sm font-semibold text-slate-700">{{ __('mous.form.scope_en') }}</label>
        <textarea
            id="scope_en"
            name="scope_en"
            rows="4"
            data-editor="tinymce"
            class="form-textarea"
        >{{ old('scope_en', $mou->scope_en ?? '') }}</textarea>
    </div>

    <div>
        <label for="key_areas_am" class="block text-sm font-semibold text-slate-700">{{ __('mous.form.key_areas_am') }}</label>
        <textarea
            id="key_areas_am"
            name="key_areas_am"
            rows="4"
            data-editor="tinymce"
            class="form-textarea"
        >{{ old('key_areas_am', $mou->key_areas_am ?? '') }}</textarea>
    </div>

    <div>
        <label for="key_areas_en" class="block text-sm font-semibold text-slate-700">{{ __('mous.form.key_areas_en') }}</label>
        <textarea
            id="key_areas_en"
            name="key_areas_en"
            rows="4"
            data-editor="tinymce"
            class="form-textarea"
        >{{ old('key_areas_en', $mou->key_areas_en ?? '') }}</textarea>
    </div>

    <div>
        <label for="attachment" class="block text-sm font-semibold text-slate-700">{{ __('mous.form.attachment') }}</label>
        <input id="attachment" name="attachment" type="file" accept=".pdf,.doc,.docx" class="form-input">
        @if(isset($mou) && $mou->attachment_path)
            <p class="text-xs text-slate-500 mt-2">{{ __('mous.form.current_attachment', ['path' => basename($mou->attachment_path)]) }}</p>
        @endif
        @error('attachment')
            <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
        @enderror
    </div>

    <div class="flex items-center gap-3">
        <input type="hidden" name="is_published" value="0">
        <label class="flex items-center gap-2 text-sm font-semibold text-slate-700">
            <input
                type="checkbox"
                name="is_published"
                value="1"
                @checked(old('is_published', $mou->is_published ?? false))
            >
            {{ __('mous.form.published') }}
        </label>
    </div>
</div>

@include('admin.components.editor-scripts')
