@extends('admin.layouts.app')

@section('content')
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-xl font-semibold">Media</h1>
            <p class="text-sm text-gray-500">Browse uploaded files or add new items.</p>
        </div>
        <button
            type="button"
            class="inline-flex items-center rounded-full bg-blue-600 px-4 py-2 text-sm font-semibold text-white shadow hover:bg-blue-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-400"
            onclick="document.getElementById('media-upload').classList.toggle('hidden')"
        >
            Create media
        </button>
    </div>

    @if(session('success'))
        <div class="mb-4 p-3 rounded bg-green-100 text-green-800">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="mb-4 p-3 rounded bg-red-100 text-red-800">{{ session('error') }}</div>
    @endif

    {{-- List --}}
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4">
        @foreach($media as $item)
            <div class="border rounded p-3">
                <div class="flex items-start justify-between gap-3">
                    <div class="min-w-0">
                        <div class="text-sm font-semibold truncate">
                            {{ $item->title ?: $item->original_name }}
                        </div>
                        <div class="text-xs text-gray-600 break-all">
                            {{ $item->original_name }}
                        </div>
                        <div class="text-xs text-gray-600 mt-1">
                            {{ $item->mime_type }} • {{ number_format($item->size) }} bytes
                        </div>
                        <div class="text-xs mt-2">
                            <a class="underline" href="{{ $item->url }}" target="_blank">Open file</a>
                        </div>
                    </div>

                    {{-- Delete --}}
                    <form method="POST" action="{{ route('admin.media.index') }}" onsubmit="return confirm('Delete this media?')">
                        @csrf
                        <input type="hidden" name="_action" value="delete">
                        <input type="hidden" name="id" value="{{ $item->id }}">
                        <button class="text-red-600 text-sm underline">Delete</button>
                    </form>
                </div>

                @if($item->is_image)
                    <img src="{{ $item->url }}" class="w-full h-40 object-cover rounded mt-3 border">
                @endif

                {{-- Update --}}
                <details class="mt-4">
                    <summary class="cursor-pointer text-sm underline">Edit</summary>

                    <form method="POST" action="{{ route('admin.media.index') }}" enctype="multipart/form-data" class="mt-3 flex flex-col gap-3">
                        @csrf
                        <input type="hidden" name="_action" value="update">
                        <input type="hidden" name="id" value="{{ $item->id }}">

                        <div>
                            <label class="block text-sm font-medium mb-1">Title</label>
                            <input class="w-full border rounded p-2" type="text" name="title" value="{{ old('title', $item->title) }}">
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-1">Replace file (optional)</label>
                            <input type="file" name="file">
                        </div>

                        <div class="flex gap-2">
                            <x-admin.button type="submit">Update</x-admin.button>
                        </div>
                    </form>
                </details>
            </div>
        @endforeach
    </div>

    <div class="mt-6">
        {{ $media->links() }}
    </div>

    <section id="media-upload" class="mt-6 hidden rounded-2xl border border-dashed border-gray-300 bg-white p-6 shadow-sm">
        <div class="flex items-center justify-between mb-4">
            <div>
                <p class="text-xs font-semibold uppercase tracking-[0.3em] text-gray-500">Upload</p>
                <h2 class="text-lg font-semibold text-gray-900">Create Media Item</h2>
            </div>
            <button
                type="button"
                class="text-sm font-medium text-blue-600 hover:text-blue-800"
                onclick="document.getElementById('media-upload').classList.add('hidden')"
            >
                Close
            </button>
        </div>

        <form method="POST" action="{{ route('admin.media.index') }}" enctype="multipart/form-data" class="space-y-4">
            @csrf
            <input type="hidden" name="_action" value="store">

            <div>
                <label class="block text-xs font-semibold uppercase tracking-wide text-gray-500">Title (optional)</label>
                <input class="mt-1 w-full rounded border border-gray-200 px-3 py-2 text-sm shadow-sm focus:border-blue-300 focus:outline-none focus:ring-1 focus:ring-blue-200" type="text" name="title" value="{{ old('title') }}">
                @error('title') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-xs font-semibold uppercase tracking-wide text-gray-500">File</label>
                <input class="mt-1 w-full rounded border border-gray-200 px-3 py-2 text-sm shadow-sm focus:border-blue-300 focus:outline-none focus:ring-1 focus:ring-blue-200" type="file" name="file" required>
                @error('file') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>

            <div>
                <x-admin.button type="submit" class="w-full">
                    Upload
                </x-admin.button>
            </div>
        </form>
    </section>
@endsection
