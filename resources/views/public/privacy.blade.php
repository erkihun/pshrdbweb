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
                        <x-rich-content class="text-justify space-y-4">
                            {!! $policy->display_body !!}
                        </x-rich-content>
                    @else
                        @foreach((array) $defaultBody as $paragraph)
                            <p class="text-sm leading-relaxed text-slate-700 first:mt-0 mt-4 text-justify">
                                {!! $paragraph !!}
                            </p>
                        @endforeach
                    @endif
                </div>
            </article>
        </div>
    </section>
@endsection
