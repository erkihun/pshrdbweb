@extends('layouts.public')

@php
    $breadcrumbItems = [
        ['label' => __('vacancies.public.title'), 'url' => route('vacancies.index')],
    ];
@endphp

@section('content')
<div class="min-h-screen bg-gradient-to-b from-slate-50 via-white to-slate-100 py-10">
    <div class="mx-auto max-w-3xl px-4 sm:px-6 lg:px-8">
        <div class="rounded-3xl border border-slate-200 bg-white px-6 py-12 text-center shadow-sm sm:px-8">
            <h1 class="text-2xl font-bold text-slate-900">Vacancy Announcements</h1>
            <p class="mt-3 text-sm leading-6 text-slate-600">
                This public section now displays active vacancy announcements only.
            </p>
            <div class="mt-6">
                <a href="{{ route('vacancies.index') }}" class="inline-flex items-center justify-center rounded-xl bg-sky-600 px-5 py-3 text-sm font-semibold text-white transition hover:bg-sky-700">
                    View Active Announcements
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
