@extends('admin.layouts.app')

@section('content')
    <div class="space-y-8 p-6">
        <div class="flex flex-wrap items-center justify-between gap-4">
                <div>
                    <h1 class="text-3xl font-bold tracking-tight text-slate-900">{{ __('common.labels.pages') }}</h1>
                    <p class="mt-2 text-sm text-slate-500">{{ __('common.nav.organization') }}</p>
                </div>
            @if(!empty($missingKeys) && $missingKeys->isNotEmpty())
                <a
                    href="{{ route('admin.pages.create') }}"
                    class="inline-flex items-center gap-2 rounded-full bg-indigo-600 px-4 py-2 text-sm font-semibold text-white transition hover:bg-indigo-500"
                >
                    {{ __('common.actions.create') }}
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                </a>
            @else
                <span class="text-sm font-medium text-slate-500">
                    {{ __('common.messages.pages_defined') }}
                </span>
            @endif
        </div>

        <div class="relative overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm transition-all hover:shadow-md">
            <table class="w-full text-left text-sm">
                <thead class="bg-slate-50/50 text-xs font-semibold uppercase tracking-wider text-slate-600 border-b border-slate-100">
                    <tr>
                        <th class="px-8 py-5">{{ __('common.labels.key') }}</th>
                        <th class="px-8 py-5">{{ __('common.labels.title') }}</th>
                        <th class="px-8 py-5">{{ __('common.labels.status') }}</th>
                        <th class="px-8 py-5 text-right">{{ __('common.actions.edit') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach ($items as $item)
                        <tr class="group hover:bg-slate-50/50 transition-colors">
                            <td class="px-8 py-5">
                                <span class="font-mono text-xs font-medium text-slate-400 bg-slate-100 px-2 py-1 rounded">
                                    {{ $item['key'] }}
                                </span>
                            </td>
                            <td class="px-8 py-5">
                                <span class="font-semibold text-slate-900">
                                    {{ $item['page']?->display_title ?? __('common.labels.not_available') }}
                                </span>
                            </td>
                            <td class="px-8 py-5">
                                @if($item['page']?->is_published)
                                    <span class="inline-flex items-center gap-1.5 rounded-full bg-emerald-50 px-3 py-1 text-xs font-medium text-emerald-700 ring-1 ring-inset ring-emerald-600/20">
                                        <span class="h-1.5 w-1.5 rounded-full bg-emerald-600"></span>
                                        {{ __('common.status.published') }}
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 rounded-full bg-slate-100 px-3 py-1 text-xs font-medium text-slate-600 ring-1 ring-inset ring-slate-500/10">
                                        <span class="h-1.5 w-1.5 rounded-full bg-slate-400"></span>
                                        {{ __('common.status.draft') }}
                                    </span>
                                @endif
                            </td>
                            <td class="px-8 py-5 text-right">
                                <a
                                    href="{{ route('admin.pages.edit', $item['key']) }}"
                                    class="inline-flex items-center rounded-lg px-3 py-2 text-sm font-semibold text-indigo-600 transition-all hover:bg-indigo-50 hover:text-indigo-700"
                                >
                                    {{ __('common.actions.edit') }}
                                    <svg class="ml-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                                    </svg>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
