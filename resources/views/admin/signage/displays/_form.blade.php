@php
    $display = $display ?? null;
    $existingPayload = optional($display)->payload;
    $payloadValue = old('payload', $existingPayload ? json_encode($existingPayload, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) : '');
    $existingPublishedAt = optional($display)->published_at?->format('Y-m-d\TH:i');
    $publishedAtValue = old('published_at', $existingPublishedAt ?? '');
@endphp

<div class="space-y-4">
    <label class="block text-sm">
        <span class="text-xs font-semibold uppercase tracking-[0.3em] text-slate-500">Template</span>
        <select
            name="signage_template_id"
            class="mt-2 w-full rounded-lg border border-slate-200 bg-white px-4 py-2 text-sm focus:border-blue-500 focus:outline-none focus:ring focus:ring-blue-200"
            required
        >
            <option value="">Select template</option>
            @foreach ($templates as $templateOption)
                <option
                    value="{{ $templateOption->uuid }}"
                    {{ old('signage_template_id', $display->signage_template_id ?? '') === $templateOption->uuid ? 'selected' : '' }}
                >
                    {{ $templateOption->name_en ?: $templateOption->name_am ?: $templateOption->slug }}
                </option>
            @endforeach
        </select>
        @error('signage_template_id')
            <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
        @enderror
    </label>

    <div class="grid gap-4 md:grid-cols-2">
        <label class="block text-sm">
            <span class="text-xs font-semibold uppercase tracking-[0.3em] text-slate-500">English Title</span>
            <input
                type="text"
                name="title_en"
                value="{{ old('title_en', $display->title_en ?? '') }}"
                class="mt-2 w-full rounded-lg border border-slate-200 bg-slate-50 px-4 py-2 text-sm focus:border-blue-500 focus:outline-none focus:ring focus:ring-blue-200"
                required
            >
            @error('title_en')
                <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
            @enderror
        </label>

        <label class="block text-sm">
            <span class="text-xs font-semibold uppercase tracking-[0.3em] text-slate-500">Amharic Title</span>
            <input
                type="text"
                name="title_am"
                value="{{ old('title_am', $display->title_am ?? '') }}"
                class="mt-2 w-full rounded-lg border border-slate-200 bg-slate-50 px-4 py-2 text-sm focus:border-blue-500 focus:outline-none focus:ring focus:ring-blue-200"
            >
            @error('title_am')
                <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
            @enderror
        </label>
    </div>

    <label class="block text-sm">
        <span class="text-xs font-semibold uppercase tracking-[0.3em] text-slate-500">Slug</span>
        <input
            type="text"
            name="slug"
            value="{{ old('slug', $display->slug ?? '') }}"
            class="mt-2 w-full rounded-lg border border-slate-200 bg-slate-50 px-4 py-2 text-sm focus:border-blue-500 focus:outline-none focus:ring focus:ring-blue-200"
        >
        @error('slug')
            <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
        @enderror
    </label>

    @php
        $existingPayload = optional($display)->payload;
        $payloadValue = old('payload', $existingPayload ? json_encode($existingPayload, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) : '');
    @endphp
    <label class="block text-sm">
        <span class="text-xs font-semibold uppercase tracking-[0.3em] text-slate-500">Payload (JSON)</span>
        <textarea
            name="payload"
            rows="6"
            class="mt-2 w-full rounded-lg border border-slate-200 bg-slate-50 px-4 py-3 text-sm focus:border-blue-500 focus:outline-none focus:ring focus:ring-blue-200"
        >{{ $payloadValue }}</textarea>
        @error('payload')
            <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
        @enderror
    </label>

    <div class="grid gap-4 md:grid-cols-2">
        <label class="block text-sm">
            <span class="text-xs font-semibold uppercase tracking-[0.3em] text-slate-500">Refresh Seconds</span>
            <input
                type="number"
                name="refresh_seconds"
                value="{{ old('refresh_seconds', $display->refresh_seconds ?? 60) }}"
                class="mt-2 w-full rounded-lg border border-slate-200 bg-slate-50 px-4 py-2 text-sm focus:border-blue-500 focus:outline-none focus:ring focus:ring-blue-200"
                min="5"
                max="3600"
            >
            @error('refresh_seconds')
                <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
            @enderror
        </label>

        <label class="block text-sm">
            <span class="text-xs font-semibold uppercase tracking-[0.3em] text-slate-500">Published At</span>
            <input
                type="datetime-local"
                name="published_at"
                value="{{ $publishedAtValue }}"
                class="mt-2 w-full rounded-lg border border-slate-200 bg-slate-50 px-4 py-2 text-sm focus:border-blue-500 focus:outline-none focus:ring focus:ring-blue-200"
            >
            @error('published_at')
                <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
            @enderror
        </label>
    </div>

    <label class="inline-flex items-center gap-3 text-sm">
        <input type="hidden" name="is_published" value="0">
        <input
            type="checkbox"
            name="is_published"
            value="1"
            class="h-4 w-4 rounded border-slate-300 text-blue-600 focus:ring-blue-500"
            {{ old('is_published', $display->is_published ?? false) ? 'checked' : '' }}
        >
        <span class="text-slate-600">Published</span>
    </label>
</div>
