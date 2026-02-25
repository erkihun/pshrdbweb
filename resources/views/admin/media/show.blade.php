@extends('admin.layouts.app')

@section('content')
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-xl font-semibold">Media Details</h1>
        <div class="flex gap-3">
            <a class="underline" href="{{ route('admin.media.edit', $medium) }}">Edit</a>

            <form method="POST" action="{{ route('admin.media.destroy', $medium) }}"
                  onsubmit="return confirm('Delete this media?')">
                @csrf
                @method('DELETE')
                <button class="text-red-600 underline">Delete</button>
            </form>
        </div>
    </div>

    @if(session('success'))
        <div class="mb-4 p-3 rounded bg-green-100 text-green-800">{{ session('success') }}</div>
    @endif

    <div class="border rounded p-4">
        <div class="mb-3"><strong>Title:</strong> {{ $medium->title ?: '—' }}</div>
        <div class="mb-3"><strong>Original name:</strong> {{ $medium->original_name }}</div>
        <div class="mb-3"><strong>MIME:</strong> {{ $medium->mime_type }}</div>
        <div class="mb-3"><strong>Size:</strong> {{ number_format($medium->size) }} bytes</div>
        <div class="mb-4"><strong>URL:</strong> <a class="underline" href="{{ $medium->url }}" target="_blank">Open</a></div>

        @if($medium->is_image)
            <img src="{{ $medium->url }}" class="max-w-full rounded border">
        @endif
    </div>

    <div class="mt-6">
        <a class="underline" href="{{ route('admin.media.index') }}">Back</a>
    </div>
@endsection
