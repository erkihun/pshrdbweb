@extends('admin.layouts.app')

@section('content')
    <div class="flex flex-col gap-6">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-semibold text-slate-900">{{ __('common.nav.news') }} &amp; {{ __('common.nav.announcements') }}</h1>
                <p class="text-sm text-slate-500">{{ __('common.nav.news') }} / {{ __('common.nav.announcements') }}</p>
            </div>
            <a
                href="{{ route('admin.posts.create') }}"
                class="inline-flex items-center rounded-lg bg-slate-900 px-4 py-2 text-sm font-semibold text-white transition hover:bg-slate-800"
            >
                {{ __('common.actions.create') }}
            </a>
        </div>

        <form class="flex flex-wrap items-end gap-4 rounded-xl border border-slate-200 bg-white p-4 shadow-sm">
            <div>
                <label class="text-xs font-semibold uppercase tracking-wide text-slate-400" for="type">{{ __('common.labels.type') }}</label>
                <select
                    id="type"
                    name="type"
                    class="mt-2 w-full rounded-lg border border-slate-200 px-3 py-2 text-sm focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-300"
                >
                    <option value="">{{ __('common.labels.all') }}</option>
                    <option value="news" @selected(request('type') === 'news')>{{ __('common.nav.news') }}</option>
                    <option value="announcement" @selected(request('type') === 'announcement')>{{ __('common.nav.announcements') }}</option>
                </select>
            </div>
            <div>
                <label class="text-xs font-semibold uppercase tracking-wide text-slate-400" for="status">{{ __('common.labels.status') }}</label>
                <select
                    id="status"
                    name="status"
                    class="mt-2 w-full rounded-lg border border-slate-200 px-3 py-2 text-sm focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-300"
                >
                    <option value="">{{ __('common.labels.all') }}</option>
                    <option value="published" @selected(request('status') === 'published')>{{ __('common.status.published') }}</option>
                    <option value="draft" @selected(request('status') === 'draft')>{{ __('common.status.draft') }}</option>
                </select>
            </div>
            <div>
                <button
                    type="submit"
                    class="inline-flex items-center rounded-lg bg-slate-900 px-4 py-2 text-sm font-semibold text-white transition hover:bg-slate-800"
                >
                    {{ __('common.actions.filter') }}
                </button>
            </div>
        </form>

        <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
            <table class="w-full text-left text-sm">
                <thead class="bg-slate-50 text-xs uppercase tracking-wide text-slate-500">
                    <tr>
                        <th class="px-6 py-3">{{ __('common.labels.title') }}</th>
                        <th class="px-6 py-3">{{ __('common.labels.type') }}</th>
                        <th class="px-6 py-3">Slug</th>
                        <th class="px-6 py-3">{{ __('common.labels.publish_date') }}</th>
                        <th class="px-6 py-3">{{ __('common.labels.status') }}</th>
                        <th class="px-6 py-3 text-right">{{ __('common.actions.view') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($posts as $post)
                        <tr>
                            <td class="px-6 py-4 font-medium text-slate-900">{{ $post->display_title }}</td>
                            <td class="px-6 py-4 text-slate-500">{{ $post->type === 'news' ? __('common.nav.news') : __('common.nav.announcements') }}</td>
                            <td class="px-6 py-4 text-slate-500">{{ $post->slug }}</td>
                            <td class="px-6 py-4 text-slate-500">
                                {{ $post->published_at ? $post->published_at->format('M d, Y H:i') : __('common.status.draft') }}
                            </td>
                            <td class="px-6 py-4">
                                <span class="{{ $post->is_published ? 'text-emerald-600' : 'text-slate-500' }}">
                                    {{ $post->is_published ? __('common.status.published') : __('common.status.draft') }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="inline-flex items-center gap-2">
                                    <a
                                        href="{{ route('admin.posts.show', $post) }}"
                                        class="text-sm font-medium text-slate-600 hover:text-slate-900"
                                    >
                                        {{ __('common.actions.view') }}
                                    </a>
                                    <a
                                        href="{{ route('admin.posts.edit', $post) }}"
                                        class="text-sm font-medium text-slate-600 hover:text-slate-900"
                                    >
                                        {{ __('common.actions.edit') }}
                                    </a>
                                    <form method="POST" action="{{ route('admin.posts.destroy', $post) }}">
                                        @csrf
                                        @method('DELETE')
                                        <button
                                            type="submit"
                                            class="text-sm font-medium text-rose-600 hover:text-rose-700"
                                            onclick="return confirm('Delete this post?')"
                                        >
                                            {{ __('common.actions.delete') }}
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-10 text-center text-slate-500">
                                No posts yet. Create your first news item.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div>
            {{ $posts->links() }}
        </div>
    </div>
@endsection

