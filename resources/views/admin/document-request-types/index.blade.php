@extends('admin.layouts.app')

@section('content')
    <div class="flex flex-col gap-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-semibold text-slate-900">{{ __('common.labels.document_request_type') }}</h1>
                <p class="text-sm text-slate-500">{{ __('common.messages.manage_document_request_types') }}</p>
            </div>
            <a href="{{ route('admin.document-request-types.create') }}" class="btn-primary">{{ __('common.actions.create') }}</a>
        </div>

        <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
            <table class="w-full text-left text-sm">
                <thead class="bg-slate-50 text-xs uppercase tracking-wide text-slate-500">
                    <tr>
                        <th class="px-6 py-3">{{ __('common.labels.title') }} (AM)</th>
                        <th class="px-6 py-3">{{ __('common.labels.title') }} (EN)</th>
                        <th class="px-6 py-3">{{ __('common.labels.status') }}</th>
                        <th class="px-6 py-3 text-right">{{ __('common.actions.actions') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($types as $type)
                        <tr>
                            <td class="px-6 py-4 text-slate-900">{{ $type->name_am }}</td>
                            <td class="px-6 py-4 text-slate-900">{{ $type->name_en }}</td>
                            <td class="px-6 py-4">
                                <span class="badge badge-{{ $type->is_active ? 'success' : 'muted' }}">{{ $type->is_active ? __('common.status.active') : __('common.status.inactive') }}</span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <a href="{{ route('admin.document-request-types.edit', $type) }}" class="text-sm font-medium text-slate-600 hover:text-slate-900">{{ __('common.actions.edit') }}</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-10 text-center text-slate-500">{{ __('common.messages.no_document_request_types') }}</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection

