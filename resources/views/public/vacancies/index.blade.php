@extends('layouts.public')

@php
    $breadcrumbItems = [
        ['label' => __('vacancies.public.title'), 'url' => route('vacancies.index')],
    ];
@endphp

@section('content')
<div class="min-h-screen bg-gradient-to-b from-slate-50 via-white to-sky-50 py-10">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <section class="rounded-3xl border border-slate-200 bg-white px-6 py-8 shadow-lg shadow-slate-200/60 sm:px-8">
                @if($vacancies->count())
                    <div class="space-y-4">
                        @foreach($vacancies as $vacancy)
                            @php
                                $safeTitle = trim(html_entity_decode(strip_tags((string) $vacancy->title), ENT_QUOTES | ENT_HTML5, 'UTF-8'));
                            @endphp

                            <article class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm transition hover:border-sky-200 hover:shadow-md sm:p-7">
                                <h2 class="text-xl font-bold leading-tight text-slate-900 sm:text-2xl">
                                    {{ $safeTitle !== '' ? $safeTitle : __('vacancies.public.title') }}
                                </h2>

                                <div class="prose mt-5 max-w-none text-sm leading-7 text-slate-700 prose-headings:text-slate-900 prose-a:text-sky-700 prose-img:rounded-2xl prose-img:shadow-sm sm:text-base">
                                    {!! $vacancy->description !!}
                                </div>
                            </article>
                        @endforeach
                    </div>

                    <div class="mt-8 border-t border-slate-200 pt-6">
                        {{ $vacancies->links() }}
                    </div>
                @else
                    <div class="rounded-3xl border border-dashed border-slate-300 bg-slate-50 px-6 py-16 text-center shadow-sm">
                        <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-2xl bg-slate-100 text-slate-500">
                            <svg class="h-8 w-8" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h3m-6 5h12a2 2 0 0 0 2-2V7.828a2 2 0 0 0-.586-1.414l-3.828-3.828A2 2 0 0 0 14.172 2H6a2 2 0 0 0-2 2v15a2 2 0 0 0 2 2Z" />
                            </svg>
                        </div>

                        <h2 class="mt-6 text-2xl font-bold tracking-tight text-slate-900">No active vacancy announcements</h2>
                        <p class="mx-auto mt-3 max-w-xl text-sm leading-7 text-slate-600">
                            There are currently no published vacancy announcements available for public application.
                            Please check again later for new openings.
                        </p>
                    </div>
                @endif
        </section>
    </div>
</div>
@endsection
