@extends('layouts.public')

@php
    $breadcrumbItems = [
        ['label' => __('vacancies.public.title'), 'url' => route('vacancies.index')],
        ['label' => $vacancy->title, 'url' => route('vacancies.show', ['slug' => $vacancy->public_slug])],
    ];
@endphp

@section('content')
<div class="min-h-screen bg-gradient-to-b from-sky-50 via-white to-slate-100 py-10">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <section class="w-full">
            <header class="overflow-hidden rounded-3xl bg-gradient-to-r from-slate-950 via-slate-900 to-sky-900 px-6 py-10 text-white sm:px-8 lg:px-10">
                <div class="flex flex-wrap items-center gap-2">
                    <span class="inline-flex items-center rounded-full bg-emerald-100 px-3 py-1 text-xs font-semibold text-emerald-700">
                        Active
                    </span>
                    <span class="inline-flex items-center rounded-full bg-white/10 px-3 py-1 text-xs font-semibold text-slate-100">
                        Deadline {{ $vacancy->displayDeadlineLabel() }}
                    </span>
                    <span class="inline-flex items-center rounded-full bg-white/10 px-3 py-1 text-xs font-semibold text-slate-100">
                        Published {{ optional($vacancy->published_at ?? $vacancy->created_at)->format('M d, Y') ?? __('common.not_available') }}
                    </span>
                </div>

                <h1 class="mt-4 max-w-4xl text-3xl font-bold tracking-tight sm:text-4xl lg:text-5xl">{{ $vacancy->title }}</h1>
            </header>

            <div class="w-full px-0 py-8 sm:py-10">
                <div class="max-w-none">
                    <div class="max-w-full overflow-x-auto">
                        <div class="prose max-w-none text-slate-700 prose-headings:text-slate-900 prose-a:text-sky-700 prose-img:rounded-2xl prose-img:shadow-sm [&_table]:min-w-full [&_table]:border-collapse [&_table]:text-sm [&_th]:whitespace-nowrap [&_td]:align-top">
                        {!! $vacancy->description !!}
                        </div>
                    </div>

                    <div class="mt-10">
                        <a href="{{ route('vacancies.index') }}" class="inline-flex items-center text-sm font-semibold text-sky-700 transition hover:text-sky-800">
                            Back to announcements
                        </a>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>
@endsection
