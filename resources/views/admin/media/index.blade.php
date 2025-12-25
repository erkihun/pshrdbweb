@extends('admin.layouts.app')

@section('content')
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-xl font-semibold">Media</h1>
    </div>

    @if(session('success'))
        <div class="mb-4 p-3 rounded bg-green-100 text-green-800">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="mb-4 p-3 rounded bg-red-100 text-red-800">{{ session('error') }}</div>
    @endif

    {{-- Upload --}}
    <div class="border rounded p-4 mb-8">
        <h2 class="font-semibold mb-3">Upload</h2>

        <form method="POST" action="{{ route('admin.media.index') }}" enctype="multipart/form-data" class="flex flex-col gap-3">
            @csrf
            <input type="hidden" name="_action" value="store">

            <div>
                <label class="block text-sm font-medium mb-1">Title (optional)</label>
                <input class="w-full border rounded p-2" type="text" name="title" value="{{ old('title') }}">
                @error('title') <div class="text-red-600 text-sm mt-1">{{ $message }}</div> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium mb-1">File</label>
                <input type="file" name="file" required>
                @error('file') <div class="text-red-600 text-sm mt-1">{{ $message }}</div> @enderror
            </div>

            <div>
                <x-admin.button type="submit">Upload</x-admin.button>
            </div>
        </form>
    </div>

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
@endsection
