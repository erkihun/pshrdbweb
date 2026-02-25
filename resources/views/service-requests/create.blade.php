<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800 leading-tight">
            {{ __('common.labels.service_request') }}
        </h2>
    </x-slot>

    <div class="py-10">
        <div class="mx-auto max-w-3xl space-y-8 sm:px-6 lg:px-8">

            {{-- Success Message --}}
            @if (session('success'))
                <div class="rounded-2xl border border-emerald-200 bg-emerald-50 px-6 py-4 text-sm text-emerald-800">
                    {{ session('success') }}
                </div>
            @endif

            {{-- Form Card --}}
            <div class="rounded-3xl border border-gray-200 bg-white p-8 shadow-sm">
                <form
                    method="POST"
                    action="{{ route('service-requests.store') }}"
                    enctype="multipart/form-data"
                    class="space-y-8"
                    aria-label="{{ __('common.labels.service_request') }}"
                >
                    @csrf

                    {{-- Personal Info --}}
                    <div class="grid gap-6 sm:grid-cols-2">
                        <div class="space-y-2">
                            <label class="text-sm font-semibold text-gray-700" for="full_name">
                                {{ __('common.labels.full_name') }}
                            </label>
                            <input
                                id="full_name"
                                name="full_name"
                                type="text"
                                value="{{ old('full_name') }}"
                                class="form-input"
                                required
                            >
                            @error('full_name')
                                <p class="text-xs text-rose-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="space-y-2">
                            <label class="text-sm font-semibold text-gray-700" for="phone">
                                {{ __('common.labels.phone') }}
                            </label>
                            <input
                                id="phone"
                                name="phone"
                                type="text"
                                value="{{ old('phone') }}"
                                class="form-input"
                            >
                            @error('phone')
                                <p class="text-xs text-rose-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    {{-- Contact & Service --}}
                    <div class="grid gap-6 sm:grid-cols-2">
                        <div class="space-y-2">
                            <label class="text-sm font-semibold text-gray-700" for="email">
                                {{ __('common.labels.email') }}
                            </label>
                            <input
                                id="email"
                                name="email"
                                type="email"
                                value="{{ old('email') }}"
                                class="form-input"
                            >
                            @error('email')
                                <p class="text-xs text-rose-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="space-y-2">
                            <label class="text-sm font-semibold text-gray-700" for="service_id">
                                {{ __('common.nav.services') }}
                            </label>

                            <div class="relative">
                                <select
                                    id="service_id"
                                    name="service_id"
                                    class="form-select w-full appearance-none pr-10"
                                >
                                    <option value="">
                                        {{ __('common.labels.all') }}
                                    </option>
                                    @foreach ($services as $service)
                                        <option
                                            value="{{ $service->id }}"
                                            @selected(old('service_id') == $service->id)
                                        >
                                            {{ $service->display_title }}
                                        </option>
                                    @endforeach
                                </select>

                                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3">
                                    <svg class="h-5 w-5 text-slate-400" viewBox="0 0 20 20" fill="none" stroke="currentColor">
                                        <path d="M6 8l4 4 4-4" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                </div>
                            </div>

                            @error('service_id')
                                <p class="text-xs text-rose-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    {{-- Subject --}}
                    <div class="space-y-2">
                        <label class="text-sm font-semibold text-gray-700" for="subject">
                            {{ __('common.labels.subject') }}
                        </label>
                        <input
                            id="subject"
                            name="subject"
                            type="text"
                            value="{{ old('subject') }}"
                            class="form-input"
                            required
                        >
                        @error('subject')
                            <p class="text-xs text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Description --}}
                    <div class="space-y-2">
                        <label class="text-sm font-semibold text-gray-700" for="description">
                            {{ __('common.labels.description') }}
                        </label>
                        <textarea
                            id="description"
                            name="description"
                            rows="6"
                            class="form-textarea"
                            required
                        >{{ old('description') }}</textarea>
                        @error('description')
                            <p class="text-xs text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Attachment --}}
                    <div class="space-y-3">
                        <label class="text-sm font-semibold text-gray-700" for="attachment">
                            {{ __('common.labels.attachment') }}
                        </label>

                        <input
                            id="attachment"
                            name="attachment"
                            type="file"
                            class="block w-full text-sm"
                            aria-describedby="attachment-help"
                        >

                        <p id="attachment-help" class="text-xs text-gray-500">
                            PDF / DOC / DOCX / JPG / PNG — max 5MB
                        </p>

                        @error('attachment')
                            <p class="text-xs text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Submit --}}
                    <div class="flex justify-end pt-4">
                        <button type="submit" class="btn-primary">
                            {{ __('common.actions.submit') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
