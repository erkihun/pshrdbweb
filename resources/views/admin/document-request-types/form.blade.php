@extends('admin.layouts.app')

@section('content')
    <div class="flex flex-col gap-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-semibold text-slate-900">
                    {{ $type->exists ? __('common.actions.edit') : __('common.actions.create') }} {{ __('common.labels.document_request_type') }}
                </h1>
            </div>
            <a href="{{ route('admin.document-request-types.index') }}" class="btn-secondary">{{ __('common.actions.back') }}</a>
        </div>

        <div class="rounded-2xl border border-slate-200 bg-white p-8 shadow-sm">
            <form method="POST" action="{{ $type->exists ? route('admin.document-request-types.update', $type) : route('admin.document-request-types.store') }}" class="space-y-4">
                @csrf
                @if ($type->exists)
                    @method('PUT')
                @endif

                <div class="grid gap-4 sm:grid-cols-2">
                    <div>
                        <label class="text-sm font-semibold text-slate-700" for="name_am">{{ __('common.labels.title') }} (AM)</label>
                        <input id="name_am" name="name_am" type="text" value="{{ old('name_am', $type->name_am) }}" class="form-input" required>
                        @error('name_am')<p class="mt-2 text-xs text-rose-600">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="text-sm font-semibold text-slate-700" for="name_en">{{ __('common.labels.title') }} (EN)</label>
                        <input id="name_en" name="name_en" type="text" value="{{ old('name_en', $type->name_en) }}" class="form-input" required>
                        @error('name_en')<p class="mt-2 text-xs text-rose-600">{{ $message }}</p>@enderror
                    </div>
                </div>

                <div>
                    <label class="text-sm font-semibold text-slate-700" for="slug">Slug</label>
                    <input id="slug" name="slug" type="text" value="{{ old('slug', $type->slug) }}" class="form-input" required>
                    @error('slug')<p class="mt-2 text-xs text-rose-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="text-sm font-semibold text-slate-700" for="requirements_am">{{ __('common.labels.requirements') }} (AM)</label>
                    <textarea id="requirements_am" name="requirements_am" rows="3" class="form-textarea">{{ old('requirements_am', $type->requirements_am) }}</textarea>
                </div>

                <div>
                    <label class="text-sm font-semibold text-slate-700" for="requirements_en">{{ __('common.labels.requirements') }} (EN)</label>
                    <textarea id="requirements_en" name="requirements_en" rows="3" class="form-textarea">{{ old('requirements_en', $type->requirements_en) }}</textarea>
                </div>

                <div class="grid gap-4 sm:grid-cols-2">
                    <div>
                        <label class="text-sm font-semibold text-slate-700" for="sort_order">{{ __('common.labels.sort_order') }}</label>
                        <input id="sort_order" name="sort_order" type="number" value="{{ old('sort_order', $type->sort_order) }}" class="form-input">
                    </div>
                    <div class="flex items-center gap-3">
                        <input id="is_active" name="is_active" type="checkbox" value="1" @checked(old('is_active', $type->is_active))>
                        <label class="text-sm font-semibold text-slate-700" for="is_active">{{ __('common.status.active') }}</label>
                    </div>
                </div>

                <div class="flex justify-end">
                    <button type="submit" class="btn-primary">{{ __('common.actions.save') }}</button>
                </div>
            </form>
        </div>
    </div>
@endsection

