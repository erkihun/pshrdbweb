@extends('admin.layouts.app')

@section('title', 'Official Message')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    <!-- Header -->
    <div class="flex items-start justify-between mb-8">
        <div class="flex items-center gap-4">
            <div class="p-3 bg-gradient-to-br from-blue-50 to-indigo-50 rounded-xl">
                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
            </div>
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Higher Official Message</h1>
                <p class="text-sm text-gray-600 mt-1">One message only. One photo only.</p>
            </div>
        </div>
    </div>


    <!-- Error Message -->
    @if($errors->any())
        <div class="mb-6 p-4 bg-gradient-to-r from-red-50 to-rose-50 border border-red-200 rounded-xl flex items-start">
            <svg class="w-5 h-5 text-red-500 mt-0.5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.998-.833-2.732 0L4.346 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
            </svg>
            <div>
                <p class="text-red-800 font-medium">Please fix the errors below.</p>
                <ul class="mt-1 text-red-600 text-sm">
                    @foreach($errors->all() as $error)
                        <li>• {{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    <!-- Form Container -->
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="border-b border-gray-200 bg-gradient-to-r from-gray-50 to-white px-6 py-4">
            <h2 class="text-lg font-semibold text-gray-900">{{ __('common.official_message_form.heading') }}</h2>
            <p class="text-sm text-gray-600 mt-1">{{ __('common.official_message_form.description') }}</p>
        </div>
        
        <div class="p-6">
            <form method="POST" action="{{ route('admin.official-message.update') }}" enctype="multipart/form-data" class="space-y-8">
                @csrf
                @method('PUT')

                <!-- Name Field -->
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <label class="block text-sm font-semibold text-gray-900">{{ __('common.official_message_form.name') }} *</label>
                        <span class="text-xs text-gray-500">{{ __('common.official_message_form.required') }}</span>
                    </div>
                    <div class="grid gap-4 md:grid-cols-2">
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 mb-1">{{ __('common.official_message_form.amharic') }}</label>
                            <input type="text"
                                   name="name_am"
                                   value="{{ old('name_am', $message->name_am ?? $message->name) }}"
                                   class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 focus:ring-opacity-50 transition-colors duration-200"
                                   placeholder="_name in Amharic_"
                                   required>
                            @error('name_am')
                                <p class="mt-2 text-sm text-red-600 flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                                    </svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 mb-1">{{ __('common.official_message_form.english') }}</label>
                            <input type="text"
                                   name="name_en"
                                   value="{{ old('name_en', $message->name_en ?? $message->name) }}"
                                   class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 focus:ring-opacity-50 transition-colors duration-200"
                                   placeholder="Name in English"
                                   required>
                            @error('name_en')
                                <p class="mt-2 text-sm text-red-600 flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                                    </svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Title Field -->
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <label class="block text-sm font-semibold text-gray-900">{{ __('common.official_message_form.title') }} *</label>
                        <span class="text-xs text-gray-500">{{ __('common.official_message_form.required') }}</span>
                    </div>
                    <div class="grid gap-4 md:grid-cols-2">
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 mb-1">{{ __('common.official_message_form.amharic') }}</label>
                            <input type="text"
                                   name="title_am"
                                   value="{{ old('title_am', $message->title_am ?? $message->title) }}"
                                   class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 focus:ring-opacity-50 transition-colors duration-200"
                                   placeholder="Title in Amharic"
                                   required>
                            @error('title_am')
                                <p class="mt-2 text-sm text-red-600 flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                                    </svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 mb-1">{{ __('common.official_message_form.english') }}</label>
                            <input type="text"
                                   name="title_en"
                                   value="{{ old('title_en', $message->title_en ?? $message->title) }}"
                                   class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 focus:ring-opacity-50 transition-colors duration-200"
                                   placeholder="Title in English"
                                   required>
                            @error('title_en')
                                <p class="mt-2 text-sm text-red-600 flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                                    </svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Message Field -->
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <label class="block text-sm font-semibold text-gray-900">{{ __('common.official_message_form.message') }} *</label>
                        <span class="text-xs text-gray-500">{{ __('common.official_message_form.required') }}</span>
                    </div>
                    <div class="grid gap-4 md:grid-cols-2">
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 mb-1">{{ __('common.official_message_form.amharic') }}</label>
                            <textarea name="message_am"
                                      rows="5"
                                      class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 focus:ring-opacity-50 transition-colors duration-200 resize-none"
                                      placeholder="Message in Amharic"
                                      required>{{ old('message_am', $message->message_am ?? $message->message) }}</textarea>
                            @error('message_am')
                                <p class="mt-2 text-sm text-red-600 flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                                    </svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 mb-1">{{ __('common.official_message_form.english') }}</label>
                            <textarea name="message_en"
                                      rows="5"
                                      class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 focus:ring-opacity-50 transition-colors duration-200 resize-none"
                                      placeholder="Message in English"
                                      required>{{ old('message_en', $message->message_en ?? $message->message) }}</textarea>
                            @error('message_en')
                                <p class="mt-2 text-sm text-red-600 flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                                    </svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Photo Field -->
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <label class="block text-sm font-semibold text-gray-900">{{ __('common.official_message_form.photo') }}</label>
                        <span class="text-xs text-gray-500">{{ __('common.official_message_form.photo_note') }}</span>
                    </div>
                    
                    <div class="mt-2">
                        <label class="flex flex-col items-center justify-center w-full p-8 border-2 border-dashed border-gray-300 rounded-lg cursor-pointer bg-gray-50 hover:bg-gray-100 transition-colors duration-200">
                            <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                <svg class="w-10 h-10 mb-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                <p class="mb-2 text-sm text-gray-500">{{ __('common.official_message_form.upload_instruction') }}</p>
                                <p class="text-xs text-gray-500">{{ __('common.official_message_form.file_types') }}</p>
                            </div>
                            <input type="file" name="photo" accept="image/*" class="hidden">
                        </label>
                    </div>

                    @error('photo') 
                        <p class="mt-2 text-sm text-red-600 flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                            </svg>
                            {{ $message }}
                        </p> 
                    @enderror

                    <!-- Current Photo Preview -->
                    @if($message->photo_path)
                        <div class="mt-6">
                            <p class="text-sm font-medium text-gray-700 mb-3">{{ __('common.official_message_form.current_photo') }}</p>
                            <div class="relative inline-block group">
                                <img src="{{ asset('storage/'.$message->photo_path) }}" 
                                     class="w-48 h-48 object-cover rounded-xl shadow-lg border border-gray-200">
                                <div class="absolute inset-0 bg-black bg-opacity-40 opacity-0 group-hover:opacity-100 rounded-xl transition-opacity duration-200 flex items-center justify-center">
                                    <span class="text-white text-sm font-medium">Current Photo</span>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Active Toggle -->
                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                    <div>
                        <label class="block text-sm font-semibold text-gray-900 mb-1">{{ __('common.official_message_form.status') }}</label>
                        <p class="text-sm text-gray-600">{{ __('common.official_message_form.status_hint') }}</p>
                    </div>
                    <div class="flex items-center">
                        <input type="hidden" name="is_active" value="0">
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" 
                                   name="is_active" 
                                   value="1" 
                                   {{ old('is_active', $message->is_active) ? 'checked' : '' }}
                                   class="sr-only peer">
                            <div class="w-11 h-6 bg-gray-300 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-200 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                            <span class="ml-3 text-sm font-medium text-gray-900">
                                {{ old('is_active', $message->is_active) ? 'Active' : 'Inactive' }}
                            </span>
                        </label>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="pt-4 border-t border-gray-200 flex justify-end">
                        <button type="submit"
                                class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-blue-600 to-blue-700 text-white font-semibold rounded-lg hover:from-blue-700 hover:to-blue-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200 shadow-sm hover:shadow-md">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path>
                            </svg>
                        {{ __('common.official_message_form.save_changes') }}
                        </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // Update status text when toggle changes
    document.querySelector('input[name="is_active"]').addEventListener('change', function(e) {
        const statusText = e.target.nextElementSibling.querySelector('span');
        statusText.textContent = e.target.checked ? 'Active' : 'Inactive';
    });

    // File upload preview
    const fileInput = document.querySelector('input[name="photo"]');
    const fileLabel = document.querySelector('label[for="photo"]');
    
    if (fileInput) {
        fileInput.addEventListener('change', function(e) {
            const fileName = e.target.files[0]?.name;
            if (fileName) {
                const uploadArea = fileLabel.parentElement;
                uploadArea.innerHTML = `
                    <div class="flex items-center justify-center p-4">
                        <svg class="w-8 h-8 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        <div>
                            <p class="text-sm font-medium text-gray-900">${fileName}</p>
                            <p class="text-xs text-gray-500">Click to change photo</p>
                        </div>
                    </div>
                `;
            }
        });
    }
</script>

<style>
    /* Smooth transitions */
    * {
        transition: background-color 0.2s ease-in-out, border-color 0.2s ease-in-out, opacity 0.2s ease-in-out;
    }

    /* Custom file upload hover */
    [type="file"] + label:hover {
        border-color: #6366f1;
    }

    /* Better textarea resize handle */
    textarea::-webkit-resizer {
        border-width: 8px;
        border-style: solid;
        border-color: transparent #d1d5db #d1d5db transparent;
    }
</style>
@endsection
