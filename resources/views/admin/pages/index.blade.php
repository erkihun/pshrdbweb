@extends('admin.layouts.app')

@section('content')
    <div class="flex flex-col gap-6">
        <div>
            <h1 class="text-2xl font-semibold text-slate-900">{{ __('common.labels.pages') }}</h1>
            <p class="text-sm text-slate-500">{{ __('common.nav.organization') }}</p>
        </div>

        <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
            <table class="w-full text-left text-sm">
                <thead class="bg-slate-50 text-xs uppercase tracking-wide text-slate-500">
                    <tr>
                        <th class="px-6 py-3">Key</th>
                        <th class="px-6 py-3">{{ __('common.labels.title') }}</th>
                        <th class="px-6 py-3">{{ __('common.labels.status') }}</th>
                        <th class="px-6 py-3">{{ __('common.actions.edit') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach ($items as $item)
                        <tr>
                            <td class="px-6 py-4 text-slate-500">{{ $item['key'] }}</td>
                            <td class="px-6 py-4 text-slate-900">
                                {{ $item['page']?->display_title ?? 'â€”' }}
                            </td>
                            <td class="px-6 py-4">
                                <span class="{{ $item['page']?->is_published ? 'text-emerald-600' : 'text-slate-500' }}">
                                    {{ $item['page']?->is_published ? __('common.status.published') : __('common.status.draft') }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <a
                                    href="{{ route('admin.pages.edit', $item['key']) }}"
                                    class="text-sm font-medium text-slate-600 hover:text-slate-900"
                                >
                                    {{ __('common.actions.edit') }}
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection

