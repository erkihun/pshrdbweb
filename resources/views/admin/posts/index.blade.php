@extends('admin.layouts.app')

@section('content')
    <div class="flex flex-col gap-8">
        <!-- Header Section -->
        <div class="flex flex-col gap-6 rounded-xl bg-gradient-to-r from-slate-900 to-slate-800 p-6 shadow-lg">
            <div class="flex flex-wrap items-center justify-between gap-4">
                <div class="flex items-center gap-3">
                    <div class="rounded-lg bg-white/10 p-3 backdrop-blur-sm">
                        <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold text-white">{{ __('common.nav.news') }} &amp; {{ __('common.nav.announcements') }}</h1>
                        <p class="text-sm text-slate-300">{{ __('common.nav.news') }} / {{ __('common.nav.announcements') }}</p>
                    </div>
                </div>
                <a
                    href="{{ route('admin.posts.create') }}"
                    class="inline-flex items-center gap-2 rounded-lg bg-white px-5 py-2.5 text-sm font-semibold text-slate-900 transition-all hover:scale-105 hover:bg-slate-100 hover:shadow-lg active:scale-95"
                >
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    {{ __('common.actions.create') }}
                </a>
            </div>
            
            <!-- Stats Cards -->
            <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
                <div class="rounded-lg bg-white/10 p-4 backdrop-blur-sm">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-slate-300">{{ __('common.status.total') }}</p>
                            <p class="text-2xl font-bold text-white">{{ $posts->total() }}</p>
                        </div>
                        <div class="rounded-lg bg-white/20 p-2">
                            <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </div>
                    </div>
                </div>
                <div class="rounded-lg bg-white/10 p-4 backdrop-blur-sm">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-slate-300">{{ __('common.status.published') }}</p>
                            <p class="text-2xl font-bold text-emerald-400">{{ $posts->where('is_published', true)->count() }}</p>
                        </div>
                        <div class="rounded-lg bg-emerald-500/20 p-2">
                            <svg class="h-5 w-5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                    </div>
                </div>
                <div class="rounded-lg bg-white/10 p-4 backdrop-blur-sm">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-slate-300">{{ __('common.status.draft') }}</p>
                            <p class="text-2xl font-bold text-amber-400">{{ $posts->where('is_published', false)->count() }}</p>
                        </div>
                        <div class="rounded-lg bg-amber-500/20 p-2">
                            <svg class="h-5 w-5 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filter Section -->
        <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
            <div class="mb-4 flex items-center gap-2">
                <svg class="h-5 w-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                </svg>
                <h2 class="text-lg font-semibold text-slate-900">{{ __('common.actions.filter') }}</h2>
            </div>
            <form class="grid grid-cols-1 gap-6 md:grid-cols-3">
                <div>
                    <label class="mb-2 block text-sm font-medium text-slate-700" for="type">{{ __('common.labels.type') }}</label>
                    <div class="relative">
                        <select
                            id="type"
                            name="type"
                            class="w-full appearance-none rounded-lg border border-slate-200 bg-white px-4 py-2.5 pl-10 pr-8 text-sm text-slate-700 transition-all hover:border-slate-300 focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-300"
                        >
                            <option value="">{{ __('common.labels.all') }}</option>
                            <option value="news" @selected(request('type') === 'news')>{{ __('common.nav.news') }}</option>
                            <option value="announcement" @selected(request('type') === 'announcement')>{{ __('common.nav.announcements') }}</option>
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                            <svg class="h-4 w-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </div>
                    </div>
                </div>
                <div>
                    <label class="mb-2 block text-sm font-medium text-slate-700" for="status">{{ __('common.labels.status') }}</label>
                    <div class="relative">
                        <select
                            id="status"
                            name="status"
                            class="w-full appearance-none rounded-lg border border-slate-200 bg-white px-4 py-2.5 pl-10 pr-8 text-sm text-slate-700 transition-all hover:border-slate-300 focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-300"
                        >
                            <option value="">{{ __('common.labels.all') }}</option>
                            <option value="published" @selected(request('status') === 'published')>{{ __('common.status.published') }}</option>
                            <option value="draft" @selected(request('status') === 'draft')>{{ __('common.status.draft') }}</option>
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                            <svg class="h-4 w-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                    </div>
                </div>
                <div class="flex items-end">
                    <button
                        type="submit"
                        class="inline-flex w-full items-center justify-center gap-2 rounded-lg bg-slate-900 px-5 py-2.5 text-sm font-semibold text-white transition-all hover:bg-slate-800 hover:shadow-lg active:scale-95 md:w-auto"
                    >
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                        {{ __('common.actions.filter') }}
                    </button>
                </div>
            </form>
        </div>

        <!-- Posts Table -->
        <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-lg">
            <div class="overflow-x-auto">
                <table class="w-full min-w-[800px] text-sm">
                    <colgroup>
                        <col style="width:10%;">
                        <col style="width:25%;">
                        <col style="width:15%;">
                        <col style="width:15%;">
                        <col style="width:15%;">
                        <col style="width:10%;">
                        <col style="width:10%;">
                        <col style="width:15%;">
                    </colgroup>
                    <thead class="bg-gradient-to-r from-slate-50 to-slate-100">
                        <tr>
                            <th class="whitespace-nowrap px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-700">Picture</th>
                            <th class="whitespace-nowrap px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-700">{{ __('common.labels.title') }}</th>
                            <th class="whitespace-nowrap px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-700">{{ __('common.labels.author_name') }}</th>
                            <th class="whitespace-nowrap px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-700">{{ __('common.labels.type') }}</th>
                            <th class="whitespace-nowrap px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-700">{{ __('common.labels.posted_date') }}</th>
                            <th class="whitespace-nowrap px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-700">{{ __('common.labels.publish_date') }}</th>
                            <th class="whitespace-nowrap px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-700">{{ __('common.labels.status') }}</th>
                            <th class="whitespace-nowrap px-6 py-4 text-right text-xs font-semibold uppercase tracking-wider text-slate-700">{{ __('common.actions.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($posts as $post)
                            <tr class="group hover:bg-slate-50/50 transition-colors duration-200">
                                <td class="px-6 py-4">
                                    <div class="relative h-12 w-12 overflow-hidden rounded-xl bg-slate-100">
                                        @if($post->cover_image_path)
                                            <img src="{{ asset('storage/' . $post->cover_image_path) }}" alt="" class="h-full w-full object-cover" loading="lazy">
                                        @else
                                            <span class="flex h-full w-full items-center justify-center text-xs font-semibold uppercase tracking-wider text-slate-500">
                                                {{ __('common.labels.photo') }}
                                            </span>
                                        @endif
                                        <div class="absolute inset-0 rounded-xl bg-gradient-to-t from-slate-900/60 to-transparent"></div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div>
                                        <div class="font-medium text-slate-900 group-hover:text-slate-800">{{ $post->display_title }}</div>
                                        @if($post->excerpt)
                                            <div class="mt-1 text-xs text-slate-500 line-clamp-1">{{ $post->excerpt }}</div>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-slate-700 font-medium">
                                        {{ filled($post->author_name) ? $post->author_name : __('common.labels.unknown') }}
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center rounded-full px-3 py-1 text-xs font-medium {{ $post->type === 'news' ? 'bg-blue-100 text-blue-800' : 'bg-purple-100 text-purple-800' }}">
                                        {{ $post->type === 'news' ? __('common.nav.news') : __('common.nav.announcements') }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-slate-700">
                                        {{ $post->posted_at ? ethiopian_date($post->posted_at, 'dd MMMM yyyy h:mm a', 'Africa/Addis_Ababa', null, 'M d, Y H:i', true) : '—' }}
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-slate-700">
                                        {{ $post->published_at ? ethiopian_date($post->published_at, 'dd MMMM yyyy h:mm a', 'Africa/Addis_Ababa', null, 'M d, Y H:i', true) : __('common.status.draft') }}
                                    </div>
                                    @if($post->published_at && $post->published_at->isFuture())
                                        <div class="mt-1 text-xs font-medium text-amber-600">Scheduled</div>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-2">
                                        <div class="h-2 w-2 rounded-full {{ $post->is_published ? 'bg-emerald-500' : 'bg-slate-400' }}"></div>
                                        <span class="text-sm font-medium {{ $post->is_published ? 'text-emerald-700' : 'text-slate-600' }}">
                                            {{ $post->is_published ? __('common.status.published') : __('common.status.draft') }}
                                        </span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="inline-flex items-center gap-3">
                                        <a
                                            href="{{ route('admin.posts.show', $post) }}"
                                            class="inline-flex items-center gap-1 rounded-lg px-3 py-1.5 text-sm font-medium text-slate-600 transition-all hover:bg-slate-100 hover:text-slate-900"
                                            title="View"
                                        >
                                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                            </svg>
                                            <span class="hidden md:inline">{{ __('common.actions.view') }}</span>
                                        </a>
                                        <a
                                            href="{{ route('admin.posts.edit', $post) }}"
                                            class="inline-flex items-center gap-1 rounded-lg px-3 py-1.5 text-sm font-medium text-blue-600 transition-all hover:bg-blue-50 hover:text-blue-700"
                                            title="Edit"
                                        >
                                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                            </svg>
                                            <span class="hidden md:inline">{{ __('common.actions.edit') }}</span>
                                        </a>
                                        <form method="POST" action="{{ route('admin.posts.destroy', $post) }}" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button
                                                type="submit"
                                                class="inline-flex items-center gap-1 rounded-lg px-3 py-1.5 text-sm font-medium text-rose-600 transition-all hover:bg-rose-50 hover:text-rose-700"
                                                onclick="return confirm('Delete this post?')"
                                                title="Delete"
                                            >
                                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                </svg>
                                                <span class="hidden md:inline">{{ __('common.actions.delete') }}</span>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-16 text-center">
                                    <div class="mx-auto max-w-md">
                                        <div class="mx-auto mb-4 h-16 w-16 text-slate-300">
                                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>
                                            </svg>
                                        </div>
                                        <h3 class="mb-2 text-lg font-semibold text-slate-900">No posts found</h3>
                                        <p class="mb-6 text-slate-500">Get started by creating your first news item or announcement.</p>
                                        <a
                                            href="{{ route('admin.posts.create') }}"
                                            class="inline-flex items-center gap-2 rounded-lg bg-slate-900 px-5 py-2.5 text-sm font-semibold text-white transition-all hover:bg-slate-800 hover:shadow-lg"
                                        >
                                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                            </svg>
                                            Create First Post
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @if($posts->hasPages())
            <div class="flex flex-col gap-3 rounded-xl border border-slate-200 bg-white p-4 shadow-sm">
                <div class="flex flex-wrap items-center justify-between gap-4 text-sm text-slate-500">
                    <div>
                        {{ __('common.appointments.pagination_range', [
                            'from' => $posts->firstItem(),
                            'to' => $posts->lastItem(),
                            'total' => $posts->total(),
                        ]) }}
                    </div>
                    <div>
                        {{ __('common.appointments.pagination_page', [
                            'current' => $posts->currentPage(),
                            'last' => $posts->lastPage(),
                        ]) }}
                    </div>
                </div>
                <div>
                    {{ $posts->appends(request()->except('page'))->links() }}
                </div>
            </div>
        @endif
    </div>

    <style>
        .line-clamp-1 {
            display: -webkit-box;
            -webkit-line-clamp: 1;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        
        .min-w-\[800px\] {
            min-width: 800px;
        }
        
        .max-w-\[200px\] {
            max-width: 200px;
        }
        
        * {
            transition: background-color 0.2s ease, color 0.2s ease, border-color 0.2s ease;
        }
    </style>
@endsection
