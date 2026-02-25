<div class="space-y-6" x-data="{ lang: 'am' }">
    <div class="grid gap-6 sm:grid-cols-2">
        <div>
            <label class="text-sm font-semibold text-slate-700" for="department_id">{{ __('common.labels.department') }}</label>
            <select
                id="department_id"
                name="department_id"
                class="mt-2 w-full rounded-lg border border-slate-200 px-4 py-2 text-sm focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-300"
            >
                <option value="">{{ __('common.labels.all') }}</option>
                @foreach ($departments as $department)
                    <option value="{{ $department->id }}" @selected(old('department_id', $staff->department_id ?? '') === $department->id)>
                        {{ $department->display_name }}
                    </option>
                @endforeach
            </select>
            @error('department_id')
                <p class="mt-2 text-xs text-rose-600">{{ $message }}</p>
            @enderror
        </div>
        <div class="flex items-center gap-3 pt-8">
            <input
                id="is_featured"
                name="is_featured"
                type="checkbox"
                value="1"
                class="h-4 w-4 rounded border-slate-300 text-slate-900 focus:ring-slate-400"
                @checked(old('is_featured', $staff->is_featured ?? false))
            >
            <label class="text-sm font-semibold text-slate-700" for="is_featured">{{ __('common.labels.featured') }}</label>
        </div>
    </div>

    <div class="flex flex-wrap items-center gap-3">
        <button
            type="button"
            class="rounded-lg px-4 py-2 text-sm font-semibold"
            :class="lang === 'am' ? 'bg-slate-900 text-white' : 'bg-slate-100 text-slate-600'"
            @click="lang = 'am'"
        >
            {{ __('common.tabs.am') }}
        </button>
        <button
            type="button"
            class="rounded-lg px-4 py-2 text-sm font-semibold"
            :class="lang === 'en' ? 'bg-slate-900 text-white' : 'bg-slate-100 text-slate-600'"
            @click="lang = 'en'"
        >
            {{ __('common.tabs.en') }}
        </button>
    </div>

    <div x-show="lang === 'am'" class="space-y-6">
        <div>
            <label class="text-sm font-semibold text-slate-700" for="full_name_am">{{ __('common.labels.full_name') }} ({{ __('common.tabs.am') }})</label>
            <input
                id="full_name_am"
                name="full_name_am"
                type="text"
                value="{{ old('full_name_am', $staff->full_name_am ?? '') }}"
                class="mt-2 w-full rounded-lg border border-slate-200 px-4 py-2 text-sm focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-300"
                required
            >
            @error('full_name_am')
                <p class="mt-2 text-xs text-rose-600">{{ $message }}</p>
            @enderror
        </div>
        <div>
            <label class="text-sm font-semibold text-slate-700" for="title_am">{{ __('common.labels.title') }} ({{ __('common.tabs.am') }})</label>
            <input
                id="title_am"
                name="title_am"
                type="text"
                value="{{ old('title_am', $staff->title_am ?? '') }}"
                class="mt-2 w-full rounded-lg border border-slate-200 px-4 py-2 text-sm focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-300"
                required
            >
            @error('title_am')
                <p class="mt-2 text-xs text-rose-600">{{ $message }}</p>
            @enderror
        </div>
        <div>
            <label class="text-sm font-semibold text-slate-700" for="bio_am">{{ __('common.labels.bio') }} ({{ __('common.tabs.am') }})</label>
            <textarea
                id="bio_am"
                name="bio_am"
                rows="4"
                class="mt-2 w-full rounded-lg border border-slate-200 px-4 py-2 text-sm focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-300"
            >{{ old('bio_am', $staff->bio_am ?? '') }}</textarea>
            @error('bio_am')
                <p class="mt-2 text-xs text-rose-600">{{ $message }}</p>
            @enderror
        </div>
    </div>

    <div x-show="lang === 'en'" class="space-y-6">
        <div>
            <label class="text-sm font-semibold text-slate-700" for="full_name_en">{{ __('common.labels.full_name') }} ({{ __('common.tabs.en') }})</label>
            <input
                id="full_name_en"
                name="full_name_en"
                type="text"
                value="{{ old('full_name_en', $staff->full_name_en ?? '') }}"
                class="mt-2 w-full rounded-lg border border-slate-200 px-4 py-2 text-sm focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-300"
                required
            >
            @error('full_name_en')
                <p class="mt-2 text-xs text-rose-600">{{ $message }}</p>
            @enderror
        </div>
        <div>
            <label class="text-sm font-semibold text-slate-700" for="title_en">{{ __('common.labels.title') }} ({{ __('common.tabs.en') }})</label>
            <input
                id="title_en"
                name="title_en"
                type="text"
                value="{{ old('title_en', $staff->title_en ?? '') }}"
                class="mt-2 w-full rounded-lg border border-slate-200 px-4 py-2 text-sm focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-300"
                required
            >
            @error('title_en')
                <p class="mt-2 text-xs text-rose-600">{{ $message }}</p>
            @enderror
        </div>
        <div>
            <label class="text-sm font-semibold text-slate-700" for="bio_en">{{ __('common.labels.bio') }} ({{ __('common.tabs.en') }})</label>
            <textarea
                id="bio_en"
                name="bio_en"
                rows="4"
                class="mt-2 w-full rounded-lg border border-slate-200 px-4 py-2 text-sm focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-300"
            >{{ old('bio_en', $staff->bio_en ?? '') }}</textarea>
            @error('bio_en')
                <p class="mt-2 text-xs text-rose-600">{{ $message }}</p>
            @enderror
        </div>
    </div>

    <div class="grid gap-6 sm:grid-cols-2">
        <div>
            <label class="text-sm font-semibold text-slate-700" for="photo">{{ __('common.labels.photo') }}</label>
            <input
                id="photo"
                name="photo"
                type="file"
                accept="image/*"
                class="mt-2 w-full rounded-lg border border-slate-200 bg-white px-4 py-2 text-sm focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-300"
            >
            @if (!empty($staff?->photo_path))
                <p class="mt-2 text-xs text-slate-500">Current: {{ $staff->photo_path }}</p>
            @endif
        </div>
        <div class="grid gap-4 sm:grid-cols-2">
            <div>
                <label class="text-sm font-semibold text-slate-700" for="phone">{{ __('common.labels.phone') }}</label>
                <input
                    id="phone"
                    name="phone"
                    type="text"
                    value="{{ old('phone', $staff->phone ?? '') }}"
                    class="mt-2 w-full rounded-lg border border-slate-200 px-4 py-2 text-sm focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-300"
                >
            </div>
            <div>
                <label class="text-sm font-semibold text-slate-700" for="email">{{ __('common.labels.email') }}</label>
                <input
                    id="email"
                    name="email"
                    type="email"
                    value="{{ old('email', $staff->email ?? '') }}"
                    class="mt-2 w-full rounded-lg border border-slate-200 px-4 py-2 text-sm focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-300"
                >
            </div>
        </div>
    </div>

    <div class="grid gap-6 sm:grid-cols-2">
        <div>
            <label class="text-sm font-semibold text-slate-700" for="sort_order">{{ __('common.labels.sort_order') }}</label>
            <input
                id="sort_order"
                name="sort_order"
                type="number"
                min="0"
                value="{{ old('sort_order', $staff->sort_order ?? 0) }}"
                class="mt-2 w-full rounded-lg border border-slate-200 px-4 py-2 text-sm focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-300"
            >
        </div>
        <div class="flex items-center gap-3 pt-8">
            <input
                id="is_active"
                name="is_active"
                type="checkbox"
                value="1"
                class="h-4 w-4 rounded border-slate-300 text-slate-900 focus:ring-slate-400"
                @checked(old('is_active', $staff->is_active ?? true))
            >
            <label class="text-sm font-semibold text-slate-700" for="is_active">{{ __('common.status.active') }}</label>
        </div>
    </div>
</div>
