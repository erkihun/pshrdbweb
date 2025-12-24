@extends('admin.layouts.app')

@section('content')
    <div class="flex flex-col gap-6">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-semibold text-slate-900">{{ __('common.nav.downloads') }}</h1>
                <p class="text-sm text-slate-500">{{ __('common.nav.downloads') }}</p>
            </div>
            <a
                href="{{ route('admin.documents.create') }}"
                class="inline-flex items-center rounded-lg bg-slate-900 px-4 py-2 text-sm font-semibold text-white transition hover:bg-slate-800"
            >
                {{ __('common.actions.upload') }}
            </a>
        </div>

        <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
            <table class="w-full text-left text-sm">
                <thead class="bg-slate-50 text-xs uppercase tracking-wide text-slate-500">
                    <tr>
                        <th class="px-6 py-3">{{ __('common.labels.title') }}</th>
                        <th class="px-6 py-3">{{ __('common.labels.category') }}</th>
                        <th class="px-6 py-3">{{ __('common.labels.type') }}</th>
                        <th class="px-6 py-3">Size</th>
                        <th class="px-6 py-3">{{ __('common.labels.status') }}</th>
                        <th class="px-6 py-3 text-right">{{ __('common.actions.view') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($documents as $document)
                        <tr>
                            <td class="px-6 py-4 font-medium text-slate-900">{{ $document->display_title }}</td>
                            <td class="px-6 py-4 text-slate-500">
                                {{ $document->category?->name ?? __('common.labels.category') }}
                            </td>
                            <td class="px-6 py-4 text-slate-500">{{ strtoupper($document->file_type) }}</td>
                            <td class="px-6 py-4 text-slate-500">
                                {{ number_format($document->file_size / 1024, 1) }} KB
                            </td>
                            <td class="px-6 py-4">
                                <span class="{{ $document->is_published ? 'text-emerald-600' : 'text-slate-500' }}">
                                    {{ $document->is_published ? __('common.status.published') : __('common.status.draft') }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="inline-flex items-center gap-2">
                                    <a
                                        href="{{ route('admin.documents.show', $document) }}"
                                        class="text-sm font-medium text-slate-600 hover:text-slate-900"
                                    >
                                        {{ __('common.actions.view') }}
                                    </a>
                                    <a
                                        href="{{ route('admin.documents.edit', $document) }}"
                                        class="text-sm font-medium text-slate-600 hover:text-slate-900"
                                    >
                                        {{ __('common.actions.edit') }}
                                    </a>
                                    <form method="POST" action="{{ route('admin.documents.destroy', $document) }}">
                                        @csrf
                                        @method('DELETE')
                                        <button
                                            type="submit"
                                            class="text-sm font-medium text-rose-600 hover:text-rose-700"
                                            onclick="return confirm('Delete this document?')"
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
                                No documents uploaded yet.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div>
            {{ $documents->links() }}
        </div>
    </div>
@endsection

