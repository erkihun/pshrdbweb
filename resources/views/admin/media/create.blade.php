@extends('admin.layouts.app')

@section('content')
    <h1 class="text-xl font-semibold mb-6">Upload Media</h1>

    <form method="POST" action="{{ route('admin.media.store') }}" enctype="multipart/form-data">
        @include('admin.media._form')
    </form>
@endsection
