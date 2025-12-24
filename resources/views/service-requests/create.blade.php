<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('common.labels.service_request') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="alert alert-success mb-6">
                    {{ session('success') }}
                </div>
            @endif

            <div class="rounded-2xl border border-gray-200 bg-white p-8 shadow-sm">
                <form method="POST" action="{{ route('service-requests.store') }}" enctype="multipart/form-data" class="space-y-6" aria-label="{{ __('common.labels.service_request') }}">
                    @csrf

                    <div class="grid gap-4 sm:grid-cols-2">
                        <div>
                            <label class="text-sm font-semibold text-gray-700" for="full_name">{{ __('common.labels.full_name') }}</label>
                            <input id="full_name" name="full_name" type="text" value="{{ old('full_name') }}" class="form-input" required>
                            @error('full_name')<p class="mt-2 text-xs text-rose-600">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="text-sm font-semibold text-gray-700" for="phone">{{ __('common.labels.phone') }}</label>
                            <input id="phone" name="phone" type="text" value="{{ old('phone') }}" class="form-input">
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
                            <label class="text-sm font-semibold text-gray-700" for="service_id">{{ __('common.nav.services') }}</label>
                            <select id="service_id" name="service_id" class="form-select">
                                <option value="">{{ __('common.labels.all') }}</option>
                                @foreach ($services as $service)
                                    <option value="{{ $service->id }}" @selected(old('service_id') == $service->id)>{{ $service->display_title }}</option>
                                @endforeach
                            </select>
                            @error('service_id')<p class="mt-2 text-xs text-rose-600">{{ $message }}</p>@enderror
                        </div>
                    </div>

                    <div>
                        <label class="text-sm font-semibold text-gray-700" for="subject">{{ __('common.labels.subject') }}</label>
                        <input id="subject" name="subject" type="text" value="{{ old('subject') }}" class="form-input" required>
                        @error('subject')<p class="mt-2 text-xs text-rose-600">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="text-sm font-semibold text-gray-700" for="description">{{ __('common.labels.description') }}</label>
                        <textarea id="description" name="description" rows="6" class="form-textarea" required>{{ old('description') }}</textarea>
                        @error('description')<p class="mt-2 text-xs text-rose-600">{{ $message }}</p>@enderror
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
