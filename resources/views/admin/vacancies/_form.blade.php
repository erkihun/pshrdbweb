@php
    $vacancy ??= null;
@endphp

<div class="space-y-6">
    <div>
        <label class="block text-sm font-semibold text-slate-700" for="deadline">Date</label>
        <input
            id="deadline"
            name="deadline"
            value="{{ old('deadline', optional($vacancy?->deadline)->format('Y-m-d')) }}"
            type="date"
            class="mt-2 w-full rounded-lg border border-slate-200 bg-white px-4 py-2.5 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500"
            required
        >
        @error('deadline')
            <p class="mt-2 text-sm text-rose-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label class="block text-sm font-semibold text-slate-700" for="title">Title</label>
        <input
            id="title"
            name="title"
            value="{{ old('title', $vacancy?->title) }}"
            type="text"
            class="mt-2 w-full rounded-lg border border-slate-200 bg-white px-4 py-2.5 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500"
            required
        >
        @error('title')
            <p class="mt-2 text-sm text-rose-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label class="block text-sm font-semibold text-slate-700" for="description">Main Content</label>
        <textarea
            id="description"
            name="description"
            rows="14"
            data-editor="tinymce"
            class="js-editor mt-2 w-full rounded-lg border border-slate-200 bg-white px-4 py-3 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500"
            required
        >{{ old('description', $vacancy?->description) }}</textarea>
        @error('description')
            <p class="mt-2 text-sm text-rose-600">{{ $message }}</p>
        @enderror
    </div>
</div>

@include('admin.components.editor-scripts')
