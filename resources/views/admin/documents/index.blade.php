@extends('admin.layouts.app')

@section('content')
    <div class="flex flex-col gap-8">
        <!-- Header Section -->
        <div class="flex flex-col gap-6 rounded-xl bg-gradient-to-r from-slate-900 to-slate-800 p-6 shadow-lg">
            <div class="flex flex-wrap items-center justify-between gap-4">
                <div class="flex items-center gap-3">
                    <div class="rounded-lg bg-white/10 p-3 backdrop-blur-sm">
                        <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold text-white">{{ __('common.nav.downloads') }}</h1>
                        <p class="text-sm text-slate-300">Manage downloadable documents and files</p>
                    </div>
                </div>
                <a
                    href="{{ route('admin.documents.create') }}"
                    class="inline-flex items-center gap-2 rounded-lg bg-white px-5 py-2.5 text-sm font-semibold text-slate-900 transition-all hover:scale-105 hover:bg-slate-100 hover:shadow-lg active:scale-95"
                >
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10"/>
                    </svg>
                    {{ __('common.actions.upload') }}
                </a>
            </div>
            
            <!-- Stats Cards -->
            <div class="grid grid-cols-1 gap-4 md:grid-cols-4">
                <div class="rounded-lg bg-white/10 p-4 backdrop-blur-sm">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-slate-300">{{ __('common.status.total') }}</p>
                            <p class="text-2xl font-bold text-white">{{ $documents->total() }}</p>
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
                            <p class="text-2xl font-bold text-emerald-400">{{ $documents->where('is_published', true)->count() }}</p>
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
                            <p class="text-sm text-slate-300">{{ __('common.status.categories') }}</p>
                            @php
                                $uniqueCategories = $documents->pluck('category')->filter()->unique('id')->count();
                            @endphp
                            <p class="text-2xl font-bold text-blue-400">{{ $uniqueCategories }}</p>
                        </div>
                        <div class="rounded-lg bg-blue-500/20 p-2">
                            <svg class="h-5 w-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                            </svg>
                        </div>
                    </div>
                </div>
                <div class="rounded-lg bg-white/10 p-4 backdrop-blur-sm">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-slate-300">Total Size</p>
                            @php
                                $totalSize = $documents->sum('file_size') / (1024 * 1024); // Convert to MB
                            @endphp
                            <p class="text-2xl font-bold text-purple-400">{{ number_format($totalSize, 1) }} MB</p>
                        </div>
                        <div class="rounded-lg bg-purple-500/20 p-2">
                            <svg class="h-5 w-5 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"/>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Documents Table -->
        <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-lg">
            <div class="overflow-x-auto">
                <table class="w-full min-w-[900px] text-sm">
                    <thead class="bg-gradient-to-r from-slate-50 to-slate-100">
                        <tr>
                            <th class="whitespace-nowrap px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-700">{{ __('common.labels.title') }}</th>
                            <th class="whitespace-nowrap px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-700">{{ __('common.labels.category') }}</th>
                            <th class="whitespace-nowrap px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-700">{{ __('common.labels.type') }}</th>
                            <th class="whitespace-nowrap px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-700">Size</th>
                            <th class="whitespace-nowrap px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-700">{{ __('common.labels.status') }}</th>
                            <th class="whitespace-nowrap px-6 py-4 text-right text-xs font-semibold uppercase tracking-wider text-slate-700">{{ __('common.actions.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($documents as $document)
                            @php
                                // Determine badge color based on file type
                                $badgeColor = 'bg-slate-100 text-slate-700';
                                if (in_array($document->file_type, ['pdf'])) {
                                    $badgeColor = 'bg-red-100 text-red-700';
                                } elseif (in_array($document->file_type, ['doc', 'docx'])) {
                                    $badgeColor = 'bg-blue-100 text-blue-700';
                                } elseif (in_array($document->file_type, ['xls', 'xlsx'])) {
                                    $badgeColor = 'bg-green-100 text-green-700';
                                } elseif (in_array($document->file_type, ['jpg', 'jpeg', 'png', 'gif'])) {
                                    $badgeColor = 'bg-purple-100 text-purple-700';
                                }
                                
                                // Determine icon background color
                                $iconBgColor = 'bg-slate-600';
                                if (in_array($document->file_type, ['pdf'])) {
                                    $iconBgColor = 'bg-red-600';
                                } elseif (in_array($document->file_type, ['doc', 'docx'])) {
                                    $iconBgColor = 'bg-blue-600';
                                } elseif (in_array($document->file_type, ['xls', 'xlsx'])) {
                                    $iconBgColor = 'bg-green-600';
                                } elseif (in_array($document->file_type, ['jpg', 'jpeg', 'png', 'gif'])) {
                                    $iconBgColor = 'bg-purple-600';
                                }
                            @endphp
                            <tr class="group hover:bg-slate-50/50 transition-colors duration-200">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="flex h-10 w-10 items-center justify-center rounded-lg {{ $iconBgColor }} group-hover:opacity-90">
                                            @if($document->file_type === 'pdf')
                                                <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                                </svg>
                                            @elseif(in_array($document->file_type, ['doc', 'docx']))
                                                <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                                </svg>
                                            @elseif(in_array($document->file_type, ['xls', 'xlsx']))
                                                <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                                </svg>
                                            @else
                                                <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                                </svg>
                                            @endif
                                        </div>
                                        <div>
                                            <div class="font-medium text-slate-900 group-hover:text-slate-800">{{ $document->display_title }}</div>
                                            @if($document->description)
                                                <div class="mt-1 text-xs text-slate-500 line-clamp-1">{{ $document->description }}</div>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    @if($document->category)
                                        <span class="inline-flex items-center rounded-full bg-blue-50 px-3 py-1 text-xs font-medium text-blue-700">
                                            {{ $document->category?->display_name ?? __('common.labels.category') }}
                                        </span>
                                    @else
                                        <span class="text-sm text-slate-400">—</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center rounded-full {{ $badgeColor }} px-3 py-1 text-xs font-medium">
                                        {{ strtoupper($document->file_type) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm font-medium text-slate-700">
                                        {{ number_format($document->file_size / 1024, 1) }} KB
                                    </div>
                                    @if($document->download_count > 0)
                                        <div class="mt-1 text-xs text-slate-500">
                                            {{ $document->download_count }} downloads
                                        </div>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-2">
                                        <div class="relative">
                                            <div class="h-2 w-2 rounded-full {{ $document->is_published ? 'bg-emerald-500' : 'bg-slate-400' }}"></div>
                                            @if($document->is_published)
                                                <div class="absolute -top-1 -right-1 h-3 w-3 rounded-full bg-emerald-500/20 animate-ping"></div>
                                            @endif
                                        </div>
                                        <span class="text-sm font-medium {{ $document->is_published ? 'text-emerald-700' : 'text-slate-600' }}">
                                            {{ $document->is_published ? __('common.status.published') : __('common.status.draft') }}
                                        </span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="inline-flex items-center gap-3">
                                        <a
                                            href="{{ route('admin.documents.show', $document) }}"
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
                                            href="{{ route('admin.documents.edit', $document) }}"
                                            class="inline-flex items-center gap-1 rounded-lg px-3 py-1.5 text-sm font-medium text-blue-600 transition-all hover:bg-blue-50 hover:text-blue-700"
                                            title="Edit"
                                        >
                                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                            </svg>
                                            <span class="hidden md:inline">{{ __('common.actions.edit') }}</span>
                                        </a>
                                        <form method="POST" action="{{ route('admin.documents.destroy', $document) }}" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button
                                                type="submit"
                                                class="inline-flex items-center gap-1 rounded-lg px-3 py-1.5 text-sm font-medium text-rose-600 transition-all hover:bg-rose-50 hover:text-rose-700"
                                                onclick="return confirm('Delete this document?')"
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
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                            </svg>
                                        </div>
                                        <h3 class="mb-2 text-lg font-semibold text-slate-900">No documents found</h3>
                                        <p class="mb-6 text-slate-500">Get started by uploading your first document.</p>
                                        <a
                                            href="{{ route('admin.documents.create') }}"
                                            class="inline-flex items-center gap-2 rounded-lg bg-slate-900 px-5 py-2.5 text-sm font-semibold text-white transition-all hover:bg-slate-800 hover:shadow-lg"
                                        >
                                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10"/>
                                            </svg>
                                            Upload First Document
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Pagination -->
        @if($documents->hasPages())
            <div class="rounded-xl border border-slate-200 bg-white px-6 py-4 shadow-sm">
                {{ $documents->links() }}
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
        
        .min-w-\[900px\] {
            min-width: 900px;
        }
        
        * {
            transition: background-color 0.2s ease, color 0.2s ease, border-color 0.2s ease;
        }
        
        @keyframes ping {
            75%, 100% {
                transform: scale(2);
                opacity: 0;
            }
        }
        
        .animate-ping {
            animation: ping 1s cubic-bezier(0, 0, 0.2, 1) infinite;
        }
    </style>
@endsection
