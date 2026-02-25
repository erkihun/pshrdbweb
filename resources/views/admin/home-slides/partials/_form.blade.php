@php
    $slide = $slide ?? null;
@endphp

<div class="space-y-8">
    <!-- Title Field -->
    <div>
        <div class="flex items-center justify-between mb-3">
            <label class="block text-sm font-semibold text-slate-900">
                {{ __('common.home_slides.title') }}
                <span class="ml-1 text-rose-500">*</span>
            </label>
            <span class="text-xs font-medium text-slate-500">{{ __('common.home_slides.required') }}</span>
        </div>
        <div class="grid gap-4 md:grid-cols-2">
            <div>
                <label class="text-xs font-semibold text-slate-500 mb-1 block">{{ __('common.home_slides.title') }}</label>
                <input type="text"
                       name="title"
                       value="{{ old('title', $slide->title ?? '') }}"
                       required
                       class="w-full rounded-lg border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 shadow-sm transition placeholder:text-slate-400 focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/20"
                       placeholder="Enter slide title">
                @error('title')
                    <div class="mt-2 flex items-center gap-2 text-xs text-rose-600">
                        <svg class="h-3 w-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span>{{ $message }}</span>
                    </div>
                @enderror
            </div>
            <div>
                <label class="text-xs font-semibold text-slate-500 mb-1 block">{{ __('common.home_slides.title_am') }}</label>
                <input type="text"
                       name="title_am"
                       value="{{ old('title_am', $slide->title_am ?? '') }}"
                       class="w-full rounded-lg border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 shadow-sm transition placeholder:text-slate-400 focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/20"
                       placeholder="Enter slide title in Amharic">
                @error('title_am')
                    <div class="mt-2 flex items-center gap-2 text-xs text-rose-600">
                        <svg class="h-3 w-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span>{{ $message }}</span>
                    </div>
                @enderror
            </div>
        </div>
    </div>

    <!-- Subtitle Field -->
    <div>
        <div class="flex items-center justify-between mb-3">
            <label class="block text-sm font-semibold text-slate-900">
                {{ __('common.home_slides.subtitle') }}
            </label>
            <span class="text-xs font-medium text-slate-500">{{ __('common.home_slides.optional') }}</span>
        </div>
        <div class="grid gap-4 md:grid-cols-2">
            <div>
                <label class="text-xs font-semibold text-slate-500 mb-1 block">{{ __('common.home_slides.subtitle') }}</label>
                <input type="text"
                       name="subtitle"
                       value="{{ old('subtitle', $slide->subtitle ?? '') }}"
                       class="w-full rounded-lg border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 shadow-sm transition placeholder:text-slate-400 focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/20"
                       placeholder="Enter slide subtitle (optional)">
                @error('subtitle')
                    <div class="mt-2 flex items-center gap-2 text-xs text-rose-600">
                        <svg class="h-3 w-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span>{{ $message }}</span>
                    </div>
                @enderror
            </div>
            <div>
                <label class="text-xs font-semibold text-slate-500 mb-1 block">{{ __('common.home_slides.subtitle_am') }}</label>
                <input type="text"
                       name="subtitle_am"
                       value="{{ old('subtitle_am', $slide->subtitle_am ?? '') }}"
                       class="w-full rounded-lg border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 shadow-sm transition placeholder:text-slate-400 focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/20"
                       placeholder="Enter slide subtitle in Amharic">
                @error('subtitle_am')
                    <div class="mt-2 flex items-center gap-2 text-xs text-rose-600">
                        <svg class="h-3 w-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span>{{ $message }}</span>
                    </div>
                @enderror
            </div>
        </div>
    </div>

    <!-- Image Upload Field -->
    <div>
        <div class="flex items-center justify-between mb-3">
            <label class="block text-sm font-semibold text-slate-900">
                {{ __('common.home_slides.image') }}
                @if($slide)
                    <span class="ml-1 text-slate-500">({{ __('common.home_slides.optional') }})</span>
                @else
                    <span class="ml-1 text-rose-500">*</span>
                @endif
            </label>
            <span class="text-xs font-medium text-slate-500">
                {{ $slide ? __('common.home_slides.image_optional') : __('common.home_slides.image_required') }}
            </span>
        </div>
        
        <!-- File Upload Area -->
        <div class="relative">
            <input type="file"
                   name="image"
                   id="image-upload"
                   {{ $slide ? '' : 'required' }}
                   class="absolute inset-0 z-10 h-full w-full cursor-pointer opacity-0"
                   onchange="previewImage(event)">
            
            <div class="rounded-lg border-2 border-dashed border-slate-300 bg-slate-50 p-6 transition hover:border-indigo-400 hover:bg-indigo-50/50">
                <div class="flex flex-col items-center justify-center text-center">
                    <div class="mb-3 flex h-12 w-12 items-center justify-center rounded-full bg-white shadow-sm">
                        <svg class="h-6 w-6 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <div class="mb-2">
                        <p class="text-sm font-medium text-slate-900">
                            <span class="text-indigo-600">{{ __('common.home_slides.upload_action') }}</span>
                            {{ __('common.home_slides.upload_or') }}
                        </p>
                        <p class="mt-1 text-xs text-slate-500">{{ __('common.home_slides.file_types') }}</p>
                    </div>
                </div>
            </div>
        </div>

        @error('image')
            <div class="mt-3 flex items-center gap-2 text-xs text-rose-600">
                <svg class="h-3 w-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <span>{{ $message }}</span>
            </div>
        @enderror

        <!-- Image Preview -->
        @if($slide)
            <div class="mt-6">
                <h4 class="mb-3 text-sm font-semibold text-slate-900">{{ __('common.home_slides.current_image') }}</h4>
                <div class="overflow-hidden rounded-lg border border-slate-200 bg-slate-50">
                    <img src="{{ asset('storage/'.$slide->image_path) }}"
                         class="h-48 w-full object-cover"
                         alt="Current slide image">
                    <div class="border-t border-slate-200 bg-white px-4 py-3">
                        <p class="text-xs text-slate-600 truncate">{{ $slide->image_path }}</p>
                    </div>
                </div>
            </div>
        @endif

        <!-- New Image Preview -->
        <div id="image-preview" class="mt-6 hidden">
            <h4 class="mb-3 text-sm font-semibold text-slate-900">{{ __('common.home_slides.new_image_preview') }}</h4>
            <div class="overflow-hidden rounded-lg border border-slate-200 bg-slate-50">
                <img id="preview" class="h-48 w-full object-cover" alt="Image preview">
                <div class="border-t border-slate-200 bg-white px-4 py-3">
                    <p id="preview-info" class="text-xs text-slate-600"></p>
                </div>
            </div>
        </div>
    </div>

    <!-- Motion & Alignment -->
    <div class="grid gap-4 md:grid-cols-2">
        <div>
            <label class="mb-1 block text-xs font-semibold text-slate-500">{{ __('common.home_slides.transition_style') }}</label>
            <p class="text-xs text-slate-500 mb-1">{{ __('common.home_slides.transition_style_hint') }}</p>
            <select
                name="transition_style"
                class="w-full rounded-lg border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 shadow-sm transition focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/20"
            >
                @foreach ([
                    'wave' => __('common.home_slides.transition_styles.wave'),
                    'glide' => __('common.home_slides.transition_styles.glide'),
                    'swirl' => __('common.home_slides.transition_styles.swirl'),
                    'drift' => __('common.home_slides.transition_styles.drift'),
                    'pulse' => __('common.home_slides.transition_styles.pulse'),
                ] as $value => $label)
                    <option value="{{ $value }}" {{ old('transition_style', $slide->transition_style ?? 'wave') === $value ? 'selected' : '' }}>
                        {{ $label }}
                    </option>
                @endforeach
            </select>
            @error('transition_style')
                <div class="mt-2 flex items-center gap-2 text-xs text-rose-600">
                    <svg class="h-3 w-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span>{{ $message }}</span>
                </div>
            @enderror
        </div>
        <div>
            <label class="mb-1 block text-xs font-semibold text-slate-500">{{ __('common.home_slides.content_alignment') }}</label>
            <p class="text-xs text-slate-500 mb-1">{{ __('common.home_slides.content_alignment_hint') }}</p>
            <select
                name="content_alignment"
                class="w-full rounded-lg border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 shadow-sm transition focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/20"
            >
                @foreach (['left' => __('common.home_slides.content_alignment_options.left'), 'center' => __('common.home_slides.content_alignment_options.center'), 'right' => __('common.home_slides.content_alignment_options.right')] as $value => $label)
                    <option value="{{ $value }}" {{ old('content_alignment', $slide->content_alignment ?? 'center') === $value ? 'selected' : '' }}>
                        {{ $label }}
                    </option>
                @endforeach
            </select>
            @error('content_alignment')
                <div class="mt-2 flex items-center gap-2 text-xs text-rose-600">
                    <svg class="h-3 w-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span>{{ $message }}</span>
                </div>
            @enderror
        </div>
    </div>

    <!-- Sort Order Field -->
    <div>
        <div class="flex items-center justify-between mb-3">
            <label class="block text-sm font-semibold text-slate-900">
                {{ __('common.home_slides.sort_order') }}
            </label>
            <span class="text-xs font-medium text-slate-500">{{ __('common.home_slides.sort_order_hint') }}</span>
        </div>
        <input type="number"
               name="sort_order"
               value="{{ old('sort_order', $slide->sort_order ?? 0) }}"
               class="w-full rounded-lg border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 shadow-sm transition focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/20"
               placeholder="0">
        <p class="mt-2 text-xs text-slate-500">{{ __('common.home_slides.sort_order_hint') }}</p>
    </div>

    <!-- Active Status -->
    <div>
        <div class="flex items-start gap-3 rounded-lg border border-slate-200 bg-slate-50/50 p-4">
            <div class="flex h-5 items-center">
                <input type="hidden" name="is_active" value="0">
                <input type="checkbox"
                       name="is_active"
                       id="is_active"
                       value="1"
                       class="h-4 w-4 rounded border-slate-300 bg-white text-indigo-600 shadow-sm transition focus:ring-indigo-500 focus:ring-offset-0"
                       {{ old('is_active', $slide->is_active ?? true) ? 'checked' : '' }}>
            </div>
            <div class="flex-1">
                <label for="is_active" class="block text-sm font-semibold text-slate-900">
                    {{ __('common.home_slides.active_status') }}
                </label>
                <p class="mt-1 text-sm text-slate-600">
                    {{ __('common.home_slides.active_hint') }}
                </p>
                <div class="mt-3 flex items-center gap-4">
                    <span class="inline-flex items-center gap-1.5 rounded-full bg-emerald-100 px-2.5 py-0.5 text-xs font-medium text-emerald-800">
                        <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        {{ __('common.home_slides.visible') }}
                    </span>
                    <span class="inline-flex items-center gap-1.5 rounded-full bg-slate-100 px-2.5 py-0.5 text-xs font-medium text-slate-800">
                        <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L6.59 6.59m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path>
                        </svg>
                        {{ __('common.home_slides.hidden') }}
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function previewImage(event) {
        const input = event.target;
        const preview = document.getElementById('preview');
        const previewContainer = document.getElementById('image-preview');
        const previewInfo = document.getElementById('preview-info');
        
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            const file = input.files[0];
            
            reader.onload = function(e) {
                preview.src = e.target.result;
                previewContainer.classList.remove('hidden');
                previewInfo.textContent = `${file.name} (${formatBytes(file.size)})`;
            };
            
            reader.readAsDataURL(file);
        } else {
            previewContainer.classList.add('hidden');
            previewInfo.textContent = '';
        }
    }

    function formatBytes(bytes, decimals = 2) {
        if (bytes === 0) return '0 Bytes';
        
        const k = 1024;
        const dm = decimals < 0 ? 0 : decimals;
        const sizes = ['Bytes', 'KB', 'MB', 'GB'];
        
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        
        return parseFloat((bytes / Math.pow(k, i)).toFixed(dm)) + ' ' + sizes[i];
    }

    document.addEventListener('DOMContentLoaded', function() {
        const fileInput = document.getElementById('image-upload');
        if (fileInput && fileInput.files.length > 0) {
            previewImage({ target: fileInput });
        }
    });
</script>
