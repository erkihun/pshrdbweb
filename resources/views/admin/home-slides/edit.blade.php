@extends('admin.layouts.app')

@section('title', 'Edit Slide')

@section('content')
<div class="w-full px-6 py-8">

    <h1 class="text-2xl font-bold mb-6">Edit Homepage Slide</h1>

    <div class="bg-white border rounded-lg p-6">
        <form method="POST"
              action="{{ route('admin.home-slides.update', $slide) }}"
              enctype="multipart/form-data"
              class="space-y-6">
            @csrf
            @method('PUT')

            @include('admin.home-slides.partials._form', ['slide' => $slide])

            <div class="flex justify-end gap-3">
                <a href="{{ route('admin.home-slides.index') }}"
                   class="px-4 py-2 border rounded">
                    Back
                </a>
                <button class="px-4 py-2 bg-blue-600 text-white rounded">
                    Update Slide
                </button>
            </div>
        </form>

    </div>
</div>
@endsection
