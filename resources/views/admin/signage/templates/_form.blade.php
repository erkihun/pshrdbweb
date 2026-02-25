@php
    $template = $template ?? null;
    $orientationValue = old('orientation', $template?->orientation ?? 'portrait');
    $layoutValue = old('layout', $template?->layout ?? 'header_two_cols_footer');
    $schemaValue = old(
        'schema',
        $template?->schema ? json_encode($template->schema, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) : ''
    );
@endphp

<div class="space-y-4">
    <div class="grid gap-4 md:grid-cols-2">
        <label class="block text-sm">
            <span class="text-xs font-semibold uppercase tracking-[0.3em] text-slate-500">English Name</span>
            <input
                type="text"
                name="name_en"
                value="{{ old('name_en', $template->name_en ?? '') }}"
                class="mt-2 w-full rounded-lg border border-slate-200 bg-slate-50 px-4 py-2 text-sm focus:border-blue-500 focus:outline-none focus:ring focus:ring-blue-200"
                required
            >
            @error('name_en')
                <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
            @enderror
        </label>

        <label class="block text-sm">
            <span class="text-xs font-semibold uppercase tracking-[0.3em] text-slate-500">Amharic Name</span>
            <input
                type="text"
                name="name_am"
                value="{{ old('name_am', $template->name_am ?? '') }}"
                class="mt-2 w-full rounded-lg border border-slate-200 bg-slate-50 px-4 py-2 text-sm focus:border-blue-500 focus:outline-none focus:ring focus:ring-blue-200"
            >
            @error('name_am')
                <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
            @enderror
        </label>
    </div>

    <div class="grid gap-4 md:grid-cols-2">
        <label class="block text-sm">
            <span class="text-xs font-semibold uppercase tracking-[0.3em] text-slate-500">Slug</span>
            <input
                type="text"
                name="slug"
                value="{{ old('slug', $template->slug ?? '') }}"
                class="mt-2 w-full rounded-lg border border-slate-200 bg-slate-50 px-4 py-2 text-sm focus:border-blue-500 focus:outline-none focus:ring focus:ring-blue-200"
            >
            @error('slug')
                <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
            @enderror
        </label>

        <label class="block text-sm">
            <span class="text-xs font-semibold uppercase tracking-[0.3em] text-slate-500">Orientation</span>
            <select
                name="orientation"
                class="mt-2 w-full rounded-lg border border-slate-200 bg-white px-4 py-2 text-sm focus:border-blue-500 focus:outline-none focus:ring focus:ring-blue-200"
            >
                <option value="portrait" {{ $orientationValue === 'portrait' ? 'selected' : '' }}>Portrait</option>
                <option value="landscape" {{ $orientationValue === 'landscape' ? 'selected' : '' }}>Landscape</option>
            </select>
            @error('orientation')
                <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
            @enderror
        </label>
    </div>

    <label class="block text-sm">
        <span class="text-xs font-semibold uppercase tracking-[0.3em] text-slate-500">Layout</span>
        <select
            name="layout"
            class="mt-2 w-full rounded-lg border border-slate-200 bg-white px-4 py-2 text-sm focus:border-blue-500 focus:outline-none focus:ring focus:ring-blue-200"
        >
            <option value="header_two_cols_footer" {{ $layoutValue === 'header_two_cols_footer' ? 'selected' : '' }}>Header / Two columns / Footer</option>
            <option value="header_body_footer" {{ $layoutValue === 'header_body_footer' ? 'selected' : '' }}>Header / Body / Footer</option>
            <option value="split_three_rows" {{ $layoutValue === 'split_three_rows' ? 'selected' : '' }}>Split / Three rows</option>
        </select>
        @error('layout')
            <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
        @enderror
    </label>

    <label class="block text-sm">
        <span class="text-xs font-semibold uppercase tracking-[0.3em] text-slate-500">Schema (JSON)</span>
        <textarea
            name="schema"
            rows="5"
            class="mt-2 w-full rounded-lg border border-slate-200 bg-slate-50 px-4 py-3 text-sm focus:border-blue-500 focus:outline-none focus:ring focus:ring-blue-200"
        >{{ $schemaValue }}</textarea>
        @error('schema')
            <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
        @enderror
    </label>

    <label class="inline-flex items-center gap-3 text-sm">
        <input
            type="hidden"
            name="is_active"
            value="0"
        >
        <input
            type="checkbox"
            name="is_active"
            value="1"
            class="h-4 w-4 rounded border-slate-300 text-blue-600 focus:ring-blue-500"
            {{ old('is_active', $template->is_active ?? true) ? 'checked' : '' }}
        >
        <span class="text-slate-600">Active</span>
    </label>
</div>
