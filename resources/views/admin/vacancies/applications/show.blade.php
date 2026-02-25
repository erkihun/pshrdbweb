@extends('admin.layouts.app')

@section('content')
    <div class="flex flex-col gap-6">
        <div class="flex flex-wrap items-center justify-between gap-4 rounded-xl bg-white p-6 shadow-sm">
            <div>
                <h1 class="text-2xl font-semibold text-slate-900">{{ __('vacancies.application.details_title') }}</h1>
                <p class="text-sm text-slate-500">{{ __('vacancies.application.details_description') }}</p>
            </div>
            <div class="flex flex-wrap gap-3">
                <a
                    href="{{ route('admin.vacancies.applications.index') }}"
                    class="inline-flex items-center gap-2 rounded-lg border border-slate-200 px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50"
                >
                    {{ __('common.actions.back') }}
                </a>
                <a
                    href="{{ route('admin.vacancies.applications.download', $application) }}"
                    class="inline-flex items-center gap-2 rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-500"
                >
                    {{ __('common.actions.download') }}
                </a>
            </div>
        </div>

        <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
            <div class="grid gap-6 md:grid-cols-2">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-wider text-slate-500">{{ __('vacancies.application.applicant') }}</p>
                    <p class="text-xl font-semibold text-slate-900">{{ $application->full_name }}</p>
                    <p class="text-sm text-slate-500">{{ __('vacancies.application.reference_code') }}: {{ $application->id }}</p>
                </div>
                <div class="space-y-2">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wider text-slate-500">{{ __('common.labels.email') }}</p>
                        <p class="text-sm text-slate-700">{{ $application->email }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wider text-slate-500">{{ __('common.labels.phone') }}</p>
                        <p class="text-sm text-slate-700">{{ $application->phone ?? '-' }}</p>
                    </div>
                </div>
            </div>

                <div class="mt-6 space-y-3">
                    <div class="flex items-center justify-between">
                        <p class="text-sm font-medium text-slate-700">{{ __('common.labels.vacancy') }}</p>
                        <span class="rounded-full bg-blue-100 px-3 py-1 text-xs font-semibold text-blue-700">{{ $application->vacancy?->title ?? __('common.labels.unknown') }}</span>
                    </div>
                <div class="border-t border-slate-200"></div>
                <div>
                    <p class="text-sm font-semibold text-slate-900">{{ __('common.labels.cover_letter') }}</p>
                    <p class="mt-2 text-sm leading-relaxed text-slate-700 whitespace-pre-line">{{ $application->cover_letter ?? __('vacancies.application.no_cover_letter') }}</p>
                </div>
            </div>
        </div>

        <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
            <h2 class="text-lg font-semibold text-slate-900">{{ __('vacancies.application.applicant') }} {{ __('vacancies.application.details_title') }}</h2>
            <div class="mt-6 grid gap-6 lg:grid-cols-3">
                <div class="space-y-3">
                    <p class="text-xs font-semibold uppercase tracking-wider text-slate-500">{{ __('vacancies.public.personal_info') }}</p>
                    <div class="flex flex-wrap items-start gap-4">
                        @if($application->profile_photo_path || $application->applicant?->profile_photo_path)
                            <div class="h-28 w-28 shrink-0 overflow-hidden rounded-lg bg-slate-100 ring-1 ring-slate-200">
                                <img
                                    src="{{ route('admin.vacancies.applications.photo', $application) }}"
                                    alt="{{ $application->full_name }}"
                                    class="h-full w-full object-cover"
                                    loading="lazy"
                                >
                            </div>
                        @endif
                        <dl class="flex-1 space-y-2 text-sm text-slate-700">
                            <div class="flex items-center justify-between gap-3">
                                <dt class="font-medium text-slate-600">{{ __('vacancies.public.date_of_birth') }}</dt>
                                <dd>{{ $application->date_of_birth?->format('M d, Y') ?? '-' }}</dd>
                            </div>
                            <div class="flex items-center justify-between gap-3">
                                <dt class="font-medium text-slate-600">{{ __('vacancies.public.gender') }}</dt>
                                <dd>{{ $application->gender ? __('vacancies.public.' . $application->gender) : '-' }}</dd>
                            </div>
                            <div class="flex items-center justify-between gap-3">
                                <dt class="font-medium text-slate-600">{{ __('vacancies.public.disability_status') }}</dt>
                                <dd>{{ $application->disability_status ? __('vacancies.public.yes') : __('vacancies.public.no') }}</dd>
                            </div>
                            <div class="flex items-center justify-between gap-3">
                                <dt class="font-medium text-slate-600">{{ __('vacancies.public.disability_type') }}</dt>
                                <dd>{{ $application->disability_type ?? '-' }}</dd>
                            </div>
                        </dl>
                    </div>
                </div>

                <div class="space-y-3">
                    <p class="text-xs font-semibold uppercase tracking-wider text-slate-500">{{ __('vacancies.public.education_details') }}</p>
                    <dl class="space-y-2 text-sm text-slate-700">
                        <div class="flex items-center justify-between gap-3">
                            <dt class="font-medium text-slate-600">{{ __('vacancies.public.education_level') }}</dt>
                            <dd>{{ $application->education_level ?? '-' }}</dd>
                        </div>
                        <div class="flex items-center justify-between gap-3">
                            <dt class="font-medium text-slate-600">{{ __('vacancies.public.field_of_study') }}</dt>
                            <dd>{{ $application->field_of_study ?? '-' }}</dd>
                        </div>
                        <div class="flex items-center justify-between gap-3">
                            <dt class="font-medium text-slate-600">{{ __('vacancies.public.university_name') }}</dt>
                            <dd>{{ $application->university_name ?? '-' }}</dd>
                        </div>
                        <div class="flex items-center justify-between gap-3">
                            <dt class="font-medium text-slate-600">{{ __('vacancies.public.graduation_year') }}</dt>
                            <dd>{{ $application->graduation_year ?? '-' }}</dd>
                        </div>
                        <div class="flex items-center justify-between gap-3">
                            <dt class="font-medium text-slate-600">{{ __('vacancies.public.gpa') }}</dt>
                            <dd>{{ $application->gpa ?? '-' }}</dd>
                        </div>
                    </dl>
                </div>

                <div class="space-y-3">
                    <p class="text-xs font-semibold uppercase tracking-wider text-slate-500">{{ __('vacancies.public.contact_info') }}</p>
                    <dl class="space-y-2 text-sm text-slate-700">
                        <div class="flex items-center justify-between gap-3">
                            <dt class="font-medium text-slate-600">{{ __('vacancies.public.address') }}</dt>
                            <dd class="text-right">{{ $application->address ?? '-' }}</dd>
                        </div>
                        <div class="flex items-center justify-between gap-3">
                            <dt class="font-medium text-slate-600">{{ __('vacancies.public.phone_number') }}</dt>
                            <dd>{{ $application->phone ?? '-' }}</dd>
                        </div>
                        <div class="flex items-center justify-between gap-3">
                            <dt class="font-medium text-slate-600">{{ __('vacancies.public.national_id_number') }}</dt>
                            <dd>{{ $application->national_id_number ?? '-' }}</dd>
                        </div>
                        <div class="flex items-center justify-between gap-3">
                            <dt class="font-medium text-slate-600">{{ __('common.labels.email') }}</dt>
                            <dd>{{ $application->email ?? '-' }}</dd>
                        </div>
                    </dl>

                    <div class="border-t border-slate-200 pt-3">
                        <p class="text-xs font-semibold uppercase tracking-wider text-slate-500">{{ __('common.labels.documents') }}</p>
                        <div class="mt-2 space-y-2 text-sm text-slate-700">
                            <div class="flex items-center justify-between gap-3">
                                <span>{{ __('vacancies.public.education_document') }}</span>
                                @if($application->education_document_path || $application->cv_path)
                                    <div class="flex items-center gap-3">
                                        <button
                                            type="button"
                                            class="text-xs font-semibold text-slate-600 hover:text-slate-800"
                                            data-modal-open="education-document"
                                        >
                                            {{ __('common.actions.view') }}
                                        </button>
                                        <a
                                            href="{{ route('admin.vacancies.applications.download', $application) }}"
                                            class="text-xs font-semibold text-blue-600 hover:text-blue-500"
                                        >
                                            {{ __('common.actions.download') }}
                                        </a>
                                    </div>
                                @else
                                    <span class="text-slate-400">{{ __('common.labels.not_available') }}</span>
                                @endif
                            </div>
                            <div class="flex items-center justify-between gap-3">
                                <span>{{ __('common.labels.submitted_at') }}</span>
                                <span>{{ $application->created_at?->format('M d, Y H:i') ?? '-' }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div
            id="education-document-modal"
            class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-900/60 p-4"
            aria-hidden="true"
        >
            <div class="w-full max-w-4xl overflow-hidden rounded-xl bg-white shadow-xl">
                <div class="flex items-center justify-between border-b border-slate-200 px-5 py-3">
                    <h3 class="text-sm font-semibold text-slate-900">{{ __('vacancies.public.education_document') }}</h3>
                    <button
                        type="button"
                        class="rounded-lg px-2 py-1 text-sm font-semibold text-slate-500 hover:bg-slate-100"
                        data-modal-close="education-document"
                        aria-label="{{ __('common.actions.close') }}"
                    >
                        {{ __('common.actions.close') }}
                    </button>
                </div>
                <div class="h-[70vh] w-full bg-slate-50">
                    <iframe
                        src="{{ route('admin.vacancies.applications.document', $application) }}"
                        class="h-full w-full"
                        title="{{ __('vacancies.public.education_document') }}"
                    ></iframe>
                </div>
            </div>
        </div>

        <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
            <h2 class="text-lg font-semibold text-slate-900">{{ __('vacancies.application.status_update_title') }}</h2>
            <p class="text-sm text-slate-500">{{ __('vacancies.application.status_update_description') }}</p>
            <form method="POST" action="{{ route('admin.vacancies.applications.update', $application) }}" class="mt-6 space-y-6">
                @csrf
                @method('PATCH')
                <div>
                    <label class="text-sm font-semibold text-slate-700" for="status">{{ __('common.labels.status') }}</label>
                    <select
                        id="status"
                        name="status"
                        class="mt-2 block w-full rounded-lg border border-slate-200 bg-white px-4 py-2 text-sm text-slate-700 shadow-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200"
                    >
                        @foreach($statuses as $status)
                            <option value="{{ $status }}" @selected(old('status', $application->status) === $status)>{{ __('common.status.' . $status) }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="text-sm font-semibold text-slate-700" for="admin_note">{{ __('common.labels.admin_note') }}</label>
                    <textarea
                        id="admin_note"
                        name="admin_note"
                        rows="4"
                        class="mt-2 w-full rounded-lg border border-slate-200 bg-white px-4 py-2 text-sm text-slate-700 shadow-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200"
                    >{{ old('admin_note', $application->admin_note) }}</textarea>
                </div>
                <button
                    type="submit"
                    class="inline-flex items-center gap-2 rounded-lg bg-blue-600 px-5 py-2.5 text-sm font-semibold text-white hover:bg-blue-500"
                >
                    {{ __('vacancies.application.status_update_action') }}
                </button>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    (() => {
        const openButtons = document.querySelectorAll('[data-modal-open="education-document"]');
        const closeButtons = document.querySelectorAll('[data-modal-close="education-document"]');
        const modal = document.getElementById('education-document-modal');

        if (!modal) {
            return;
        }

        const openModal = () => {
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            modal.setAttribute('aria-hidden', 'false');
        };

        const closeModal = () => {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            modal.setAttribute('aria-hidden', 'true');
        };

        openButtons.forEach((button) => {
            button.addEventListener('click', openModal);
        });

        closeButtons.forEach((button) => {
            button.addEventListener('click', closeModal);
        });

        modal.addEventListener('click', (event) => {
            if (event.target === modal) {
                closeModal();
            }
        });
    })();
</script>
@endpush
