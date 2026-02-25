<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $type->displayName() }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="alert alert-success mb-6">
                    {{ session('success') }}
                </div>
            @endif

            <div class="rounded-2xl border border-gray-200 bg-white p-8 shadow-sm space-y-4">
                <p class="text-sm text-gray-600">{{ app()->getLocale() === 'am' ? $type->requirements_am : $type->requirements_en }}</p>
                <form method="POST" action="{{ route('document-requests.store', $type->slug) }}" enctype="multipart/form-data" class="space-y-4">
                    @csrf

                    <div class="grid gap-4 sm:grid-cols-2">
                        <div>
                            <label class="text-sm font-semibold text-gray-700" for="full_name">{{ __('common.labels.full_name') }}</label>
                            <input id="full_name" name="full_name" type="text" value="{{ old('full_name') }}" class="form-input" required>
                            @error('full_name')<p class="mt-2 text-xs text-rose-600">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="text-sm font-semibold text-gray-700" for="phone">{{ __('common.labels.phone') }}</label>
                            <input id="phone" name="phone" type="text" value="{{ old('phone') }}" class="form-input" required>
                            @error('phone')<p class="mt-2 text-xs text-rose-600">{{ $message }}</p>@enderror
                        </div>
                    </div>

                    <div class="grid gap-4 sm:grid-cols-2">
                        <div>
                            <label class="text-sm font-semibold text-gray-700" for="email">{{ __('common.labels.email') }}</label>
                            <input id="email" name="email" type="email" value="{{ old('email') }}" class="form-input">
                            @error('email')<p class="mt-2 text-xs text-rose-600">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="text-sm font-semibold text-gray-700" for="id_number">{{ __('common.labels.id_number') }}</label>
                            <input id="id_number" name="id_number" type="text" value="{{ old('id_number') }}" class="form-input">
                            @error('id_number')<p class="mt-2 text-xs text-rose-600">{{ $message }}</p>@enderror
                        </div>
                    </div>

                    <div>
                        <label class="text-sm font-semibold text-gray-700" for="address_am">{{ __('common.labels.address_am') }}</label>
                        <textarea id="address_am" name="address_am" rows="2" class="form-textarea">{{ old('address_am') }}</textarea>
                    </div>
                    <div>
                        <label class="text-sm font-semibold text-gray-700" for="address_en">{{ __('common.labels.address_en') }}</label>
                        <textarea id="address_en" name="address_en" rows="2" class="form-textarea">{{ old('address_en') }}</textarea>
                    </div>

                    <div>
                        <label class="text-sm font-semibold text-gray-700" for="purpose">{{ __('common.labels.purpose') }}</label>
                        <textarea id="purpose" name="purpose" rows="4" class="form-textarea">{{ old('purpose') }}</textarea>
                        @error('purpose')<p class="mt-2 text-xs text-rose-600">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="text-sm font-semibold text-gray-700" for="attachment">{{ __('common.labels.attachment') }}</label>
                        <input id="attachment" name="attachment" type="file" class="mt-2 block w-full text-sm" aria-describedby="attachment-help">
                        <p id="attachment-help" class="mt-1 text-xs text-gray-500">PDF/DOC/DOCX/JPG/PNG up to 5MB.</p>
                        @error('attachment')<p class="mt-2 text-xs text-rose-600">{{ $message }}</p>@enderror
                    </div>

                    <div class="flex justify-end">
                        <button type="submit" class="btn-primary">{{ __('common.actions.submit') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
