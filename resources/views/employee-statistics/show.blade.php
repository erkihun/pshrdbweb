@extends('layouts.public')

@section('title', __('public.employee_statistics.title'))

@section('content')
    <div class="min-h-screen bg-white py-16 font-noto-ethiopic">
        <div class="mx-auto max-w-7xl px-6 lg:px-8">
            <div class="mb-10 space-y-3 text-center">
                <p class="text-xs uppercase tracking-wider text-slate-400">{{ __('public.employee_statistics.subtitle') }}</p>
                <h1 class="text-4xl font-semibold text-slate-900">{{ __('public.employee_statistics.title') }}</h1>
                <p class="text-sm text-slate-500 max-w-3xl mx-auto">{{ __('public.employee_statistics.description') }}</p>
            </div>

            <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3 mb-12">
                <div class="rounded-2xl border border-slate-200 bg-white p-6 text-center shadow-sm">
                    <p class="text-xs uppercase tracking-wide text-slate-400">{{ __('public.employee_statistics.total_servants') }}</p>
                    <p class="mt-3 text-3xl font-semibold text-slate-900">{{ number_format($totals['total']) }}</p>
                </div>
                <div class="rounded-2xl border border-slate-200 bg-white p-6 text-center shadow-sm">
                    <p class="text-xs uppercase tracking-wide text-slate-400">{{ __('public.employee_statistics.male') }}</p>
                    <p class="mt-3 text-3xl font-semibold text-slate-900">{{ number_format($totals['male']) }}</p>
                </div>
                <div class="rounded-2xl border border-slate-200 bg-white p-6 text-center shadow-sm">
                    <p class="text-xs uppercase tracking-wide text-slate-400">{{ __('public.employee_statistics.female') }}</p>
                    <p class="mt-3 text-3xl font-semibold text-slate-900">{{ number_format($totals['female']) }}</p>
                </div>
            </div>

            <div class="overflow-hidden rounded-3xl border border-gray-100 bg-white shadow-lg">
                <table class="min-w-full text-sm text-left">
                    <thead class="bg-slate-50">
                        <tr>
                            <th class="px-6 py-3 text-xs uppercase  text-slate-500">Organization</th>
                            <th class="px-6 py-3 text-xs uppercase  text-slate-500">Male</th>
                            <th class="px-6 py-3 text-xs uppercase  text-slate-500">Female</th>
                            <th class="px-6 py-3 text-xs uppercase  text-slate-500">Total</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($summaries as $summary)
                            <tr class="hover:bg-slate-50 transition">
                                <td class="px-6 py-4">
                                    <p class="text-lg font-semibold text-slate-900">{{ $summary['name'] }}</p>
                                    <p class="text-xs text-slate-500">{{ __('public.employee_statistics.code') }} {{ $summary['code'] ?? 'N/A' }}</p>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold uppercase  text-slate-600">
                                        {{ number_format($summary['male']) }} {{ __('public.employee_statistics.male') }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold uppercase  text-slate-600">
                                        {{ number_format($summary['female']) }} {{ __('public.employee_statistics.female') }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center rounded-full bg-blue-50 px-3 py-1 text-xs font-semibold uppercase  text-blue-600">
                                        {{ number_format($summary['total']) }} {{ __('public.employee_statistics.total') }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
