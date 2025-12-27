<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $service->display_title }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="rounded-2xl border border-gray-200 bg-white p-8 shadow-sm">
                <div class="text-sm text-gray-500">aapublicservice</div>
                <p class="mt-2 text-xs font-semibold uppercase tracking-wide text-gray-400">
                    {{ __('common.labels.last_updated') }}: {{ $service->updated_at ? ethiopian_date($service->updated_at, 'dd MMMM yyyy') : '' }}
                </p>
                @if ($feedbackCount > 0)
                    <div class="mt-3 flex items-center gap-3 text-sm text-gray-700">
                        <div class="flex items-center gap-1">
                            <span class="font-semibold">{{ $averageRating }}</span>
                            <span>/5</span>
                        </div>
                        <span class="text-gray-500">({{ $feedbackCount }} {{ __('common.labels.feedback_count') }})</span>
                    </div>
                @endif
                <div class="mt-4 text-sm text-gray-700 whitespace-pre-line">
                    {{ $service->display_description }}
                </div>

                @if ($service->display_requirements)
                    <div class="mt-6 border-t border-gray-100 pt-6">
                        <h3 class="text-sm font-semibold text-gray-900">{{ __('common.labels.requirements') }}</h3>
                        <p class="mt-2 text-sm text-gray-700 whitespace-pre-line">
                            {{ $service->display_requirements }}
                        </p>
                    </div>
                @endif

                <div class="mt-8 print-hidden">
                    <a
                        href="{{ route('services.index') }}"
                        class="btn-secondary"
                    >
                        {{ __('common.actions.back') }}
                    </a>
                </div>
            </div>

            <div class="mt-8 rounded-2xl border border-gray-200 bg-white p-8 shadow-sm print-hidden">
                <h2 class="text-lg font-semibold text-gray-900">{{ __('common.labels.rate_service') }}</h2>
                @if (session('success'))
                    <div class="alert alert-success mt-4">{{ session('success') }}</div>
                @endif
                <form method="POST" action="{{ route('services.feedback.store', $service->slug) }}" class="mt-6 space-y-4">
                    @csrf
                    <div>
                        <label class="text-sm font-semibold text-gray-700" for="rating">{{ __('common.labels.rating') }}</label>
                        <select id="rating" name="rating" class="form-select" required>
                            <option value="">{{ __('common.labels.choose') }}</option>
                            @for ($i = 1; $i <= 5; $i++)
                                <option value="{{ $i }}" @selected(old('rating') == $i)>{{ $i }}</option>
                            @endfor
                        </select>
                        @error('rating')<p class="mt-2 text-xs text-rose-600">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="text-sm font-semibold text-gray-700" for="comment">{{ __('common.labels.comment') }}</label>
                        <textarea id="comment" name="comment" rows="4" class="form-textarea">{{ old('comment') }}</textarea>
                        @error('comment')<p class="mt-2 text-xs text-rose-600">{{ $message }}</p>@enderror
                    </div>
                    <div class="grid gap-4 sm:grid-cols-3">
                        <div>
                            <label class="text-sm font-semibold text-gray-700" for="full_name">{{ __('common.labels.full_name') }}</label>
                            <input id="full_name" name="full_name" type="text" value="{{ old('full_name') }}" class="form-input">
                            @error('full_name')<p class="mt-2 text-xs text-rose-600">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="text-sm font-semibold text-gray-700" for="phone">{{ __('common.labels.phone') }}</label>
                            <input id="phone" name="phone" type="text" value="{{ old('phone') }}" class="form-input">
                            @error('phone')<p class="mt-2 text-xs text-rose-600">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="text-sm font-semibold text-gray-700" for="email">{{ __('common.labels.email') }}</label>
                            <input id="email" name="email" type="email" value="{{ old('email') }}" class="form-input">
                            @error('email')<p class="mt-2 text-xs text-rose-600">{{ $message }}</p>@enderror
                        </div>
                    </div>
                    <div class="flex justify-end">
                        <button type="submit" class="btn-primary">{{ __('common.actions.submit') }}</button>
                    </div>
                </form>

                @if ($feedbackCount > 0)
                    <div class="mt-8 space-y-4">
                        <h3 class="text-sm font-semibold uppercase tracking-wide text-gray-500">{{ __('common.labels.recent_feedback') }}</h3>
                        @foreach ($feedback as $item)
                            <div class="rounded-xl border border-gray-100 bg-gray-50 p-4">
                                <div class="flex items-center gap-2 text-sm font-semibold text-gray-900">
                                    <span>{{ $item->rating }}/5</span>
                                    <span class="text-xs text-gray-500">{{ $item->submitted_at ? ethiopian_date($item->submitted_at, 'dd MMMM yyyy') : '' }}</span>
                                </div>
                                @if ($item->comment)
                                    <p class="mt-2 text-sm text-gray-700 whitespace-pre-line">{{ $item->comment }}</p>
                                @endif
                                <p class="mt-2 text-xs text-gray-500">{{ $item->full_name ?? __('common.labels.anonymous') }}</p>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
