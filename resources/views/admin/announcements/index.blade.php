@extends('admin.layouts.app')

@section('content')
    <div class="flex flex-col gap-6">
        <div class="rounded-xl bg-white p-6 shadow-sm">
            <div class="flex flex-wrap items-center justify-between gap-4">
                <div>
                    <p class="text-sm font-semibold text-slate-500">{{ __('common.nav.announcements') }}</p>
                    <h1 class="text-2xl font-bold text-slate-900">{{ __('vacancies.application.announcements_heading') }}</h1>
                </div>
                <a
                    href="{{ route('admin.announcements.create') }}"
                    class="inline-flex items-center gap-2 rounded-lg bg-slate-900 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-800"
                >
                    {{ __('common.actions.create') }}
                </a>
            </div>
        </div>

        <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
            <form class="grid gap-4 md:grid-cols-3" method="GET" action="{{ route('admin.announcements.index') }}">
                <div>
                    <label class="mb-2 block text-sm font-semibold text-slate-700" for="search">{{ __('common.actions.search') }}</label>
                    <input
                        id="search"
                        name="search"
                        type="search"
                        value="{{ request('search') }}"
                        class="w-full rounded-lg border border-slate-200 bg-white px-4 py-2 text-sm text-slate-700 shadow-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200"
                        placeholder="{{ __('vacancies.application.search_placeholder') }}"
                    >
                </div>
                <div>
                    <label class="mb-2 block text-sm font-semibold text-slate-700" for="status">{{ __('common.labels.status') }}</label>
                    <select
                        id="status"
                        name="status"
                        class="w-full rounded-lg border border-slate-200 bg-white px-4 py-2 text-sm text-slate-700 shadow-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200"
                    >
                        <option value="">{{ __('common.labels.all') }}</option>
                        <option value="published" @selected(request('status') === 'published')>{{ __('common.status.published') }}</option>
                        <option value="draft" @selected(request('status') === 'draft')>{{ __('common.status.draft') }}</option>
                    </select>
                </div>
                <div class="flex items-end gap-3">
                    <button
                        type="submit"
                        class="inline-flex items-center gap-2 rounded-lg bg-slate-900 px-5 py-2.5 text-sm font-semibold text-white hover:bg-slate-800"
                    >
                        {{ __('common.actions.filter') }}
                    </button>
                    <a href="{{ route('admin.announcements.index') }}" class="inline-flex items-center gap-2 rounded-lg border border-slate-200 px-4 py-2 text-sm font-semibold text-slate-600 hover:bg-slate-50">
                        {{ __('common.actions.clear') }}
                    </a>
                </div>
            </form>
        </div>

        <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-lg">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-slate-50 text-xs font-semibold uppercase tracking-wider text-slate-600">
                        <tr>
                            <th class="px-6 py-4 text-left">{{ __('common.labels.title') }}</th>
                            <th class="px-6 py-4 text-left">{{ __('common.labels.posted_date') }}</th>
                            <th class="px-6 py-4 text-left">{{ __('common.labels.publish_date') }}</th>
                            <th class="px-6 py-4 text-left">{{ __('common.labels.status') }}</th>
                            <th class="px-6 py-4 text-right">{{ __('common.actions.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($posts as $announcement)
                            <tr class="group hover:bg-slate-50/70">
                                <td class="px-6 py-4 font-semibold text-slate-900">{{ $announcement->display_title }}</td>
                                <td class="px-6 py-4 text-slate-600">{{ $announcement->posted_at ? $announcement->posted_at->format('M d, Y') : '-' }}</td>
                                <td class="px-6 py-4 text-slate-600">{{ $announcement->published_at ? $announcement->published_at->format('M d, Y') : __('common.status.draft') }}</td>
                                <td class="px-6 py-4">
                                    <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold uppercase tracking-wide text-slate-700">
                                        {{ $announcement->is_published ? __('common.status.published') : __('common.status.draft') }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('admin.announcements.edit', $announcement) }}" class="inline-flex items-center gap-2 rounded-lg border border-slate-200 px-3 py-1.5 text-xs font-semibold text-slate-600 hover:bg-slate-50">
                                            {{ __('common.actions.edit') }}
                                        </a>
                                        <form method="POST" action="{{ route('admin.announcements.destroy', $announcement) }}" onsubmit="return confirm('{{ __('vacancies.application.confirm_delete') }}')" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="inline-flex items-center gap-2 rounded-lg border border-rose-200 px-3 py-1.5 text-xs font-semibold text-rose-600 hover:bg-rose-50">
                                                {{ __('common.actions.delete') }}
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center text-sm text-slate-500">
                                    {{ __('vacancies.application.announcements_empty') }}
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm">
            {{ $posts->appends(request()->except('page'))->links() }}
        </div>
    </div>
@endsection
