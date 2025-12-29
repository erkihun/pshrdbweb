@extends('admin.layouts.app')

@section('title', 'Homepage Slides')

@section('content')
<div class="w-full px-4 sm:px-6 lg:px-8 py-8">

    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
        <div>
            <h1 class="text-2xl md:text-3xl font-bold text-gray-900">{{ __('common.home_slides.heading') }}</h1>
            <p class="mt-2 text-sm text-gray-600">
                {{ __('common.home_slides.description') }}
            </p>
        </div>

        <a href="{{ route('admin.home-slides.create') }}"
           class="inline-flex items-center px-4 py-2.5 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition-colors duration-200 shadow-sm">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            {{ __('common.home_slides.add_slide') }}
        </a>
    </div>

    <!-- Success Message -->
    @if(session('success'))
        <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg flex items-start">
            <svg class="w-5 h-5 text-green-500 mt-0.5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <span class="text-green-800">{{ session('success') }}</span>
        </div>
    @endif

    <!-- Table Container -->
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">{{ __('common.home_slides.table.image') }}</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">{{ __('common.home_slides.table.title') }}</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">{{ __('common.home_slides.table.order') }}</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">{{ __('common.home_slides.table.status') }}</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">{{ __('common.home_slides.table.actions') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 bg-white">
                    @forelse($slides as $slide)
                        <tr class="hover:bg-gray-50 transition-colors duration-150">
                            <td class="px-6 py-4">
                                <div class="w-32 h-20 rounded-lg overflow-hidden border border-gray-200">
                                    <img src="{{ asset('storage/'.$slide->image_path) }}" 
                                         alt="{{ $slide->title }}"
                                         class="w-full h-full object-cover">
                                </div>
                            </td>
                            <td class="px-6 py-4 space-y-1">
                                <div class="font-semibold text-gray-900">{{ $slide->title }}</div>
                                @if($slide->title_am)
                                    <div class="text-xs text-slate-500 italic">{{ $slide->title_am }}</div>
                                @endif
                                @if($slide->subtitle)
                                    <div class="text-sm text-gray-600 mt-1">{{ $slide->subtitle }}</div>
                                @endif
                                @if($slide->subtitle_am)
                                    <div class="text-xs text-slate-500 italic">{{ $slide->subtitle_am }}</div>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-blue-100 text-blue-800 font-semibold text-sm">
                                    {{ $slide->sort_order }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                @if($slide->is_active)
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800">
                                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                        </svg>
                                        {{ __('common.home_slides.status_active') }}
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-800">
                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                        </svg>
                                        {{ __('common.home_slides.status_inactive') }}
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center space-x-3">
                                    <a href="{{ route('admin.home-slides.edit', $slide) }}"
                                       class="inline-flex items-center px-3 py-1.5 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 transition-colors duration-200">
                                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                        Edit
                                    </a>

                                    <form action="{{ route('admin.home-slides.destroy', $slide) }}"
                                          method="POST"
                                          class="inline"
                                          onsubmit="return confirm('Delete this slide?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="inline-flex items-center px-3 py-1.5 border border-red-300 rounded-lg text-sm font-medium text-red-700 bg-white hover:bg-red-50 transition-colors duration-200">
                                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                            Delete
                                        </button>
                                    </form>
                                    <button
                                        type="button"
                                        data-slide-preview
                                        data-title="{{ $slide->title }}"
                                        data-subtitle="{{ $slide->subtitle }}"
                                        data-title-am="{{ $slide->title_am }}"
                                        data-subtitle-am="{{ $slide->subtitle_am }}"
                                        data-image="{{ asset('storage/'.$slide->image_path) }}"
                                        data-transition="{{ $slide->transition_style ?? 'wave' }}"
                                        data-alignment="{{ $slide->content_alignment ?? 'center' }}"
                                        class="inline-flex items-center px-3 py-1.5 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 transition-colors duration-200"
                                    >
                                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-3.276A1 1 0 0121 7.605v8.79a1 1 0 01-1.447.895L15 14l-5 3v-8z"/>
                                        </svg>
                                        View
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center">
                                <div class="mx-auto w-16 h-16 text-gray-300 mb-4">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                                <h3 class="text-lg font-medium text-gray-900 mb-2">{{ __('common.home_slides.empty_title') }}</h3>
                                <p class="text-gray-500 mb-4">{{ __('common.home_slides.empty_description') }}</p>
                                <a href="{{ route('admin.home-slides.create') }}"
                                   class="inline-flex items-center px-4 py-2 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition-colors duration-200">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                    </svg>
                                    {{ __('common.home_slides.add_first') }}
                                </a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Slide Preview Modal -->
<div id="slide-preview-modal" class="fixed inset-0 z-50 hidden items-center justify-center px-4 py-6">
    <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" data-slide-preview-close></div>
    <div class="relative w-full max-w-3xl rounded-2xl border border-white/20 bg-white shadow-2xl overflow-hidden">
            <div class="relative h-72 bg-gray-900 overflow-hidden preview-motion" id="slide-preview-motion">
                <img id="slide-preview-image" src="" alt="Slide preview image" class="absolute inset-0 h-full w-full object-cover opacity-80">
                <div class="absolute inset-0 bg-gradient-to-b from-black/30 via-black/40 to-black/60"></div>
            <button type="button" data-slide-preview-close class="absolute right-4 top-4 inline-flex h-9 w-9 items-center justify-center rounded-full bg-white/80 text-gray-900 hover:bg-white shadow-lg hover:ring-2 hover:ring-offset-2 hover:ring-indigo-400" aria-label="Close preview">
                <span class="sr-only">Close preview</span>
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
            <div class="relative z-10 flex h-full flex-col justify-end p-6 text-white">
                <p id="slide-preview-transition" class="text-xs uppercase tracking-[0.3em] text-white/70">Preview</p>
                <h3 id="slide-preview-title" class="text-3xl font-bold leading-tight">Slide Title</h3>
                <p id="slide-preview-subtitle" class="mt-2 text-sm text-white/80">Slide subtitle will appear here.</p>
            </div>
        </div>
        <div class="p-6">
            <div class="flex flex-wrap items-center gap-4 text-sm text-gray-600">
                <span id="slide-preview-alignment" class="px-3 py-1 rounded-full border border-gray-200">Center</span>
                <span class="inline-flex items-center gap-1 text-xs uppercase tracking-[0.4em] text-gray-500">
                    <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    Active
                </span>
            </div>
        </div>
    </div>
</div>

    <script>
        (function() {
            const modal = document.getElementById('slide-preview-modal');
            const btns = document.querySelectorAll('[data-slide-preview]');
            const titleEl = document.getElementById('slide-preview-title');
            const subtitleEl = document.getElementById('slide-preview-subtitle');
            const imageEl = document.getElementById('slide-preview-image');
            const transitionEl = document.getElementById('slide-preview-transition');
            const alignmentEl = document.getElementById('slide-preview-alignment');
            const motionEl = document.getElementById('slide-preview-motion');
            const closeElements = modal.querySelectorAll('[data-slide-preview-close]');
            const motionClasses = ['motion-wave', 'motion-glide', 'motion-swirl', 'motion-drift', 'motion-pulse'];

            function setMotionClass(style) {
                if(!motionEl) return;
                motionClasses.forEach((cls) => motionEl.classList.remove(cls));
                motionEl.classList.add(`motion-${style}`);
            }

            function openPreview(button) {
                const title = button.dataset.title || 'Slide Title';
                const subtitle = button.dataset.subtitle || button.dataset.subtitleAm || '';
                const transition = button.dataset.transition || 'wave';
                const alignment = button.dataset.alignment || 'center';
                const image = button.dataset.image || '';

                titleEl.textContent = title;
                subtitleEl.textContent = subtitle;
                transitionEl.textContent = transition.toUpperCase();
                alignmentEl.textContent = alignment.charAt(0).toUpperCase() + alignment.slice(1);
                imageEl.src = image;
                imageEl.setAttribute('alt', title);
                setMotionClass(transition);

                modal.classList.remove('hidden');
                document.body.classList.add('overflow-hidden');
            }

            function closePreview() {
                modal.classList.add('hidden');
                document.body.classList.remove('overflow-hidden');
            }

            btns.forEach(btn => btn.addEventListener('click', () => openPreview(btn)));
            closeElements.forEach(el => el.addEventListener('click', closePreview));
        })();
    </script>

<style>
    /* Custom scrollbar styling */
    .overflow-x-auto::-webkit-scrollbar {
        height: 6px;
    }
    
    .overflow-x-auto::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 3px;
    }
    
    .overflow-x-auto::-webkit-scrollbar-thumb {
        background: #cbd5e0;
        border-radius: 3px;
    }
    
    .overflow-x-auto::-webkit-scrollbar-thumb:hover {
        background: #a0aec0;
    }
    
    /* Smooth hover transitions */
    * {
        transition: background-color 0.2s ease-in-out, border-color 0.2s ease-in-out;
    }
</style>
<style>
    .preview-motion.motion-wave {
        animation: previewWave 1.2s ease-in-out both;
    }
    .preview-motion.motion-glide {
        animation: previewGlide 1s ease-in-out both;
    }
    .preview-motion.motion-swirl {
        animation: previewSwirl 1.15s ease-in-out both;
    }
    .preview-motion.motion-drift {
        animation: previewDrift 1.1s ease-in-out both;
    }
    .preview-motion.motion-pulse {
        animation: previewPulse 0.9s ease-in-out both;
    }

    @keyframes previewWave {
        from {
            transform: translateX(-30px);
            opacity: 0.6;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }

    @keyframes previewGlide {
        from {
            transform: translateX(-40px) scale(0.98);
            opacity: 0.7;
        }
        to {
            transform: translateX(0) scale(1);
            opacity: 1;
        }
    }

    @keyframes previewSwirl {
        0% {
            transform: translate3d(-20px, 15px, 0) rotate(4deg);
            opacity: 0.65;
        }
        100% {
            transform: translateX(0) rotate(0);
            opacity:1;
        }
    }

    @keyframes previewDrift {
        from {
            transform: translateY(20px);
            opacity: 0.6;
        }
        to {
            transform: translateY(0);
            opacity: 1;
        }
    }

    @keyframes previewPulse {
        0% { transform: scale(0.95); opacity: 0.7; }
        50% { transform: scale(1.03); }
        100% { transform: scale(1); opacity: 1; }
    }
</style>
@endsection
