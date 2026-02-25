@extends('admin.layouts.app')

@section('content')
    <h1 class="text-xl font-semibold mb-6">Edit Media</h1>

    <form method="POST" action="{{ route('admin.media.update', $medium) }}" enctype="multipart/form-data">
        @method('PUT')
        @include('admin.media._form', ['medium' => $medium])
    </form>
@endsection
