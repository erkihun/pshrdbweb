@extends('admin.layouts.app')

@section('content')
    {{ $slot ?? '' }}
    @yield('content')
@endsection
