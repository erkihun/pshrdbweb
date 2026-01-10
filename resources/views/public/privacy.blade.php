@extends('layouts.public')

@php
    $metaTitle = $title ?? __('public.privacy_page.title');
    $metaDescription = $description ?? __('public.privacy_page.description');
@endphp

@php
    $defaultBody = trans('public.privacy_page.body');
@endphp

@section('content')
    <section class="bg-slate-50 py-5">
        <div class="mx-auto max-w-5xl px-4 sm:px-6 lg:px-8">
            <div class="rounded-3xl border border-white/50 bg-white/80 shadow-2xl shadow-slate-500/5 p-6 sm:p-10">
               
                <h1 class=" text-3xl font-bold leading-tight text-slate-900">
                  {{ __('public.footer.privacy_policy') }}
                </h1>
                <p class="mt-4 text-base text-slate-600 sm:text-lg">
                    {{ $metaDescription }}
                </p>
            </div>
        </div>
    </section>

    <section class="py-5">
        <div class="mx-auto max-w-5xl px-4 sm:px-6 lg:px-8">
            <article class="rounded-3xl border border-slate-200 bg-white shadow-lg shadow-slate-200/60">
                <div class="p-6 sm:p-10">
                    @if($policy)
                        <div class="prose prose-slate max-w-none text-justify leading-relaxed space-y-6 whitespace-pre-line">
                            {!! $policy->display_body !!}
                        </div>
                    @else
                        <div class="flex flex-col space-y-4 text-sm leading-relaxed text-slate-700 whitespace-pre-line">
                            @foreach((array) $defaultBody as $paragraph)
                                <p class="first:mt-0">
                                    {!! $paragraph !!}
                                </p>
                            @endforeach
                        </div>
                    @endif
                </div>
            </article>
        </div>
    </section>
@endsection
