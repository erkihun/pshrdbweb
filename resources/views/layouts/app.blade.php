@extends('layouts.public')

@section('content')
    <div class="mx-auto w-full max-w-6xl px-4 sm:px-6 lg:px-8">
        <x-breadcrumbs class="mb-6" />
    </div>

    {{ $slot }}
@endsection
