@extends('admin.layouts.app')

@section('content')
    <div class="flex flex-col gap-6">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-semibold text-slate-900">{{ __('common.labels.category') }}</h1>
                <p class="text-sm text-slate-500">{{ __('common.nav.downloads') }}</p>
            </div>
            <a
                href="{{ route('admin.document-categories.create') }}"
                class="inline-flex items-center rounded-lg bg-slate-900 px-4 py-2 text-sm font-semibold text-white transition hover:bg-slate-800"
            >
                {{ __('common.actions.create') }}
            </a>
        </div>

        <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
            <table class="w-full text-left text-sm">
                <thead class="bg-slate-50 text-xs uppercase tracking-wide text-slate-500">
                    <tr>
                        <th class="px-6 py-3">{{ __('common.labels.title') }}</th>
                        <th class="px-6 py-3">Slug</th>
                        <th class="px-6 py-3">{{ __('common.labels.sort_order') }}</th>
                        <th class="px-6 py-3">{{ __('common.labels.status') }}</th>
                        <th class="px-6 py-3 text-right">{{ __('common.actions.view') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($categories as $category)
                        <tr>
                            <td class="px-6 py-4 font-medium text-slate-900">{{ $category->display_name }}</td>
                            <td class="px-6 py-4 text-slate-500">{{ $category->slug }}</td>
                            <td class="px-6 py-4 text-slate-500">{{ $category->sort_order }}</td>
                            <td class="px-6 py-4">
                                <span class="{{ $category->is_active ? 'text-emerald-600' : 'text-slate-500' }}">
                                    {{ $category->is_active ? __('common.status.active') : __('common.status.inactive') }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="inline-flex items-center gap-2">
                                    <a
                                        href="{{ route('admin.document-categories.show', $category) }}"
                                        class="text-sm font-medium text-slate-600 hover:text-slate-900"
                                    >
                                        {{ __('common.actions.view') }}
                                    </a>
                                    <a
                                        href="{{ route('admin.document-categories.edit', $category) }}"
                                        class="text-sm font-medium text-slate-600 hover:text-slate-900"
                                    >
                                        {{ __('common.actions.edit') }}
                                    </a>
                                    <form method="POST" action="{{ route('admin.document-categories.destroy', $category) }}">
                                        @csrf
                                        @method('DELETE')
                                        <button
                                            type="submit"
                                            class="text-sm font-medium text-rose-600 hover:text-rose-700"
                                            onclick="return confirm('Delete this category?')"
                                        >
                                            {{ __('common.actions.delete') }}
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-10 text-center text-slate-500">
                                No categories yet. Create your first category.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div>
            {{ $categories->links() }}
        </div>
    </div>
@endsection

