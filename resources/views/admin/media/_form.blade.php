@csrf

<div class="mb-4">
    <label class="block text-sm font-medium mb-1">Title (optional)</label>
    <input type="text" name="title" value="{{ old('title', $medium->title ?? '') }}" class="w-full border rounded p-2">
    @error('title') <div class="text-red-600 text-sm mt-1">{{ $message }}</div> @enderror
</div>

<div class="mb-4">
    <label class="block text-sm font-medium mb-1">
        File {{ isset($medium) ? '(leave empty to keep current)' : '' }}
    </label>
    <input type="file" name="file" {{ isset($medium) ? '' : 'required' }}>
    @error('file') <div class="text-red-600 text-sm mt-1">{{ $message }}</div> @enderror
</div>

<div class="flex gap-2">
    <x-admin.button type="submit">Save</x-admin.button>
    <a href="{{ route('admin.media.index') }}" class="text-sm underline">Cancel</a>
</div>
