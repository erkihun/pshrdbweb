@php
    $vacancy ??= null;
@endphp

<div class="space-y-6">
    <div class="grid gap-4 md:grid-cols-2">
        <div>
            <label class="block text-sm font-semibold text-slate-700" for="title">{{ __('common.labels.title') }}</label>
            <input
                id="title"
                name="title"
                value="{{ old('title', $vacancy?->title) }}"
                type="text"
                class="mt-1 w-full rounded-lg border border-slate-200 bg-white px-4 py-2 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500"
                required
            >
        </div>
        <div>
            <label class="block text-sm font-semibold text-slate-700" for="location">{{ __('vacancies.public.location') }}</label>
            <input
                id="location"
                name="location"
                value="{{ old('location', $vacancy?->location) }}"
                type="text"
                class="mt-1 w-full rounded-lg border border-slate-200 bg-white px-4 py-2 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500"
            >
        </div>
    </div>

    <div class="grid gap-4 md:grid-cols-4">
        <div>
            <label class="block text-sm font-semibold text-slate-700" for="slots">{{ __('vacancies.admin.slots') }}</label>
            <input
                id="slots"
                name="slots"
                value="{{ old('slots', $vacancy?->slots) }}"
                type="number"
                min="1"
                class="mt-1 w-full rounded-lg border border-slate-200 bg-white px-4 py-2 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500"
                required
            >
        </div>
        <div>
            <label class="block text-sm font-semibold text-slate-700" for="deadline">{{ __('vacancies.public.deadline') }}</label>
            <input
                id="deadline"
                name="deadline"
                value="{{ old('deadline', optional($vacancy?->deadline)->format('Y-m-d')) }}"
                type="date"
                class="mt-1 w-full rounded-lg border border-slate-200 bg-white px-4 py-2 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500"
            >
        </div>
        <div>
            <label class="block text-sm font-semibold text-slate-700" for="status">{{ __('common.labels.status') }}</label>
            <select
                id="status"
                name="status"
                class="mt-1 w-full rounded-lg border border-slate-200 bg-white px-4 py-2 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500"
                required
            >
                @foreach($statuses as $status)
                    <option value="{{ $status }}" @selected(old('status', $vacancy?->status) === $status)>{{ __('common.status.' . $status) }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-sm font-semibold text-slate-700" for="published_at">{{ __('common.labels.publish_date') }}</label>
            <input
                id="published_at"
                name="published_at"
                value="{{ old('published_at', optional($vacancy?->published_at)->format('Y-m-d')) }}"
                type="date"
                class="mt-1 w-full rounded-lg border border-slate-200 bg-white px-4 py-2 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500"
            >
        </div>
    </div>

    <div class="flex items-center gap-3">
        <input id="is_published" name="is_published" type="checkbox" value="1" class="h-4 w-4 rounded" @if(old('is_published', $vacancy?->is_published)) checked @endif>
        <label for="is_published" class="text-sm font-semibold text-slate-700">{{ __('vacancies.admin.publish_immediately') }}</label>
    </div>

    <div>
        <label class="block text-sm font-semibold text-slate-700" for="code">{{ __('common.labels.reference_code') }}</label>
        <input
            id="code"
            name="code"
            value="{{ old('code', $vacancy?->code) }}"
            type="text"
            class="mt-1 w-full rounded-lg border border-slate-200 bg-white px-4 py-2 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500"
        >
    </div>

    <div>
        <label class="block text-sm font-semibold text-slate-700" for="description">{{ __('common.labels.description') }}</label>
        <textarea
            id="description"
            name="description"
            rows="4"
            class="mt-1 w-full rounded-lg border border-slate-200 bg-white px-4 py-2 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500"
        >{{ old('description', $vacancy?->description) }}</textarea>
    </div>

    <div>
        <label class="block text-sm font-semibold text-slate-700" for="notes">{{ __('vacancies.admin.notes') }}</label>
        <textarea
            id="notes"
            name="notes"
            rows="3"
            class="mt-1 w-full rounded-lg border border-slate-200 bg-white px-4 py-2 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500"
        >{{ old('notes', $vacancy?->notes) }}</textarea>
    </div>
</div>
