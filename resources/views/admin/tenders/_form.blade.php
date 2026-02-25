@props([
    'tender' => null,
    'statuses' => [],
    'action' => '',
    'method' => 'POST',
])

<form action="{{ $action }}" method="POST" class="space-y-6 rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
    @csrf
    @if(in_array($method, ['PUT', 'PATCH'], true))
        @method($method)
    @endif

    <div>
        <label class="block text-sm font-medium text-slate-700">Title</label>
        <input type="text" name="title" value="{{ old('title', $tender->title ?? '') }}" required class="mt-2 w-full rounded-lg border border-slate-200 px-4 py-2 text-sm focus:border-blue-500 focus:ring-blue-500">
        @error('title')<p class="mt-1 text-xs text-rose-500">{{ $message }}</p>@enderror
    </div>

    <div>
        <label class="block text-sm font-medium text-slate-700">Description</label>
        <textarea name="description" rows="5" class="mt-2 w-full rounded-lg border border-slate-200 px-4 py-2 text-sm focus:border-blue-500 focus:ring-blue-500">{{ old('description', $tender->description ?? '') }}</textarea>
        @error('description')<p class="mt-1 text-xs text-rose-500">{{ $message }}</p>@enderror
    </div>

    <div class="grid gap-4 md:grid-cols-3">
        <div>
            <label class="block text-sm font-medium text-slate-700">Budget</label>
            <input type="number" name="budget" step="0.01" value="{{ old('budget', $tender->budget ?? '') }}" class="mt-2 w-full rounded-lg border border-slate-200 px-4 py-2 text-sm focus:border-blue-500 focus:ring-blue-500">
            @error('budget')<p class="mt-1 text-xs text-rose-500">{{ $message }}</p>@enderror
        </div>
        <div>
            <label class="block text-sm font-medium text-slate-700">Status</label>
            <select name="status" class="mt-2 w-full rounded-lg border border-slate-200 px-4 py-2 text-sm focus:border-blue-500 focus:ring-blue-500">
                @foreach($statuses as $status)
                    <option value="{{ $status }}" @selected(old('status', $tender->status ?? 'open') === $status)>{{ ucfirst($status) }}</option>
                @endforeach
            </select>
            @error('status')<p class="mt-1 text-xs text-rose-500">{{ $message }}</p>@enderror
        </div>
        <div>
        <label class="block text-sm font-medium text-slate-700">Published at</label>
        <input
            type="datetime-local"
            name="published_at"
            value="{{ old('published_at', isset($tender) && $tender->published_at ? $tender->published_at->format('Y-m-d\TH:i') : '') }}"
            class="mt-2 w-full rounded-lg border border-slate-200 px-4 py-2 text-sm focus:border-blue-500 focus:ring-blue-500"
        >
        @error('published_at')<p class="mt-1 text-xs text-rose-500">{{ $message }}</p>@enderror
        </div>
    </div>

    <div class="flex justify-end gap-2">
        <a href="{{ route('admin.tenders.index') }}" class="rounded-lg border border-slate-200 px-4 py-2 text-sm font-semibold text-slate-600 hover:bg-slate-50">Cancel</a>
        <button type="submit" class="rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-blue-500">Save</button>
    </div>
</form>
