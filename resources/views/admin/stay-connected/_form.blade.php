<div class="space-y-6 rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
    <div class="grid gap-6 md:grid-cols-2">
        <div>
            <label for="title_am" class="block text-sm font-semibold text-slate-700">Title (Amharic)</label>
            <input
                id="title_am"
                name="title_am"
                type="text"
                value="{{ old('title_am', $stayConnected->title_am ?? '') }}"
                class="mt-2 w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm"
            >
            @error('title_am') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
        </div>
        <div>
            <label for="title_en" class="block text-sm font-semibold text-slate-700">Title (English)</label>
            <input
                id="title_en"
                name="title_en"
                type="text"
                value="{{ old('title_en', $stayConnected->title_en ?? '') }}"
                class="mt-2 w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm"
            >
            @error('title_en') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
        </div>
    </div>

    <div class="grid gap-6 md:grid-cols-2">
        <div>
            <label for="embed_url" class="block text-sm font-semibold text-slate-700">Embed URL (for iframe)</label>
            <input
                id="embed_url"
                name="embed_url"
                type="url"
                required
                value="{{ old('embed_url', $stayConnected->embed_url ?? '') }}"
                class="mt-2 w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm"
                placeholder="https://example.com/"
            >
            @error('embed_url') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
        </div>
        <div>
            <label for="url" class="block text-sm font-semibold text-slate-700">External URL (optional)</label>
            <input
                id="url"
                name="url"
                type="url"
                value="{{ old('url', $stayConnected->url ?? '') }}"
                class="mt-2 w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm"
                placeholder="https://example.com/"
            >
            @error('url') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
        </div>
    </div>

    <div class="grid gap-6 md:grid-cols-2">
        <div>
            <label for="sort_order" class="block text-sm font-semibold text-slate-700">Sort order</label>
            <input
                id="sort_order"
                name="sort_order"
                type="number"
                min="0"
                value="{{ old('sort_order', $stayConnected->sort_order ?? 0) }}"
                class="mt-2 w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm"
            >
            @error('sort_order') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
        </div>
        <div class="flex items-center gap-3 pt-8">
            <input
                id="is_active"
                name="is_active"
                type="checkbox"
                value="1"
                class="h-4 w-4 rounded border-slate-300"
                @checked(old('is_active', $stayConnected->is_active ?? true))
            >
            <label for="is_active" class="text-sm font-semibold text-slate-700">Active</label>
        </div>
    </div>

    @if(!empty($stayConnected?->embed_url))
        <div class="rounded-2xl border border-slate-200">
            <iframe
                src="{{ $stayConnected->embed_url }}"
                class="h-64 w-full rounded-2xl"
                loading="lazy"
                style="border:none;overflow:hidden"
                title="Stay connected preview"
            ></iframe>
        </div>
    @endif

    <div class="flex items-center justify-between">
        <a href="{{ route('admin.stay-connected.index') }}" class="text-sm font-semibold text-slate-500 hover:text-slate-700">
            {{ __('common.actions.cancel') }}
        </a>
        <button type="submit" class="inline-flex items-center rounded-lg bg-slate-900 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-800">
            {{ __('common.actions.save') }}
        </button>
    </div>
</div>
