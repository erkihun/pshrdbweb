@php
    $staffTitle = __('common.nav.staff');
    $seoMeta = [
        'title' => $staffTitle,
        'description' => 'Explore our staff directory and leadership at Addis Ababa public service.',
        'url' => route('staff.index'),
        'canonical' => route('staff.index'),
    ];
@endphp

<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-xl font-semibold text-gray-900 leading-tight">
                {{ __('common.labels.staff') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="grid gap-8 lg:grid-cols-[minmax(260px,320px)_minmax(0,1fr)] lg:gap-12">

                {{-- SIDEBAR --}}
                <aside class="lg:sticky lg:top-24">
                    <div class="rounded-3xl border border-gray-200 bg-white shadow-sm">
                        <div class="flex items-center justify-between border-b border-gray-100 px-5 py-4">
                            <div>
                                <p class="text-xs font-semibold uppercase tracking-wider text-gray-400">
                                    {{ __('common.labels.departments') }}
                                </p>
                                <p class="mt-1 text-sm font-semibold text-gray-900">
                                    {{ __('common.labels.departments') }}
                                </p>
                            </div>
                            <span class="rounded-full bg-gray-100 px-2.5 py-1 text-xs font-semibold text-gray-600">
                                {{ $departments->count() }}
                            </span>
                        </div>

                        <div class="max-h-[72vh] overflow-y-auto p-4 pr-3 space-y-3">

                            {{-- ALL --}}
                            <a
                                href="{{ route('staff.index') }}"
                                class="group flex items-center justify-between rounded-2xl border border-dashed border-gray-300 bg-gray-50 px-4 py-3 text-sm font-semibold text-gray-900 transition hover:border-gray-900 hover:bg-white"
                            >
                                <span class="inline-flex items-center gap-2">
                                    <span class="h-2 w-2 rounded-full bg-gray-400 group-hover:bg-gray-900"></span>
                                    {{ __('common.labels.all') }}
                                </span>
                                <span class="text-xs text-gray-400 group-hover:text-gray-700">→</span>
                            </a>

                            {{-- DEPARTMENTS --}}
                            @foreach ($departments as $department)
                                @php
                                    $active = request('department') === $department->slug;
                                @endphp

                                <a
                                    href="{{ request()->fullUrlWithQuery(['department' => $department->slug, 'page' => 1]) }}"
                                    class="group block rounded-2xl border bg-white px-4 py-4 transition hover:shadow-md
                                        {{ $active ? 'border-blue-500 bg-blue-50/60 shadow-sm' : 'border-gray-200 hover:border-blue-300' }}"
                                >
                                    <div class="flex items-start justify-between gap-3">
                                        <div class="min-w-0">
                                            <p class="truncate text-sm font-semibold text-gray-900">
                                                {{ $department->display_name }}
                                            </p>
                                            @if ($department->description)
                                                <p class="mt-1 text-xs text-gray-500 line-clamp-2">
                                                    {{ $department->description }}
                                                </p>
                                            @endif
                                        </div>

                                        <span class="shrink-0 rounded-full bg-gray-100 px-2 py-1 text-[11px] font-semibold uppercase tracking-wider text-gray-600
                                            {{ $active ? 'bg-blue-100 text-blue-700' : '' }}">
                                            {{ $department->charter_services_count ?? '' }}
                                        </span>
                                    </div>

                                    @if ($department->hasLocationDetails())
                                        <div class="mt-3 space-y-1 text-xs text-gray-600">
                                            @if($department->building_name)
                                                <div class="flex items-center gap-2">
                                                    <span class="inline-flex h-5 w-5 items-center justify-center rounded-lg bg-gray-100 text-gray-600">🏢</span>
                                                    <span class="truncate">Building: {{ $department->building_name }}</span>
                                                </div>
                                            @endif

                                            @if($department->floor_number)
                                                <div class="flex items-center gap-2">
                                                    <span class="inline-flex h-5 w-5 items-center justify-center rounded-lg bg-gray-100 text-gray-600">🧱</span>
                                                    <span>Floor: {{ $department->floor_number }}</span>
                                                </div>
                                            @endif

                                            @if($department->side_label)
                                                <div class="flex items-center gap-2">
                                                    <span class="inline-flex h-5 w-5 items-center justify-center rounded-lg bg-gray-100 text-gray-600">↔️</span>
                                                    <span>Side: {{ $department->side_label }}</span>
                                                </div>
                                            @endif

                                            @if($department->office_room)
                                                <div class="flex items-center gap-2">
                                                    <span class="inline-flex h-5 w-5 items-center justify-center rounded-lg bg-gray-100 text-gray-600">🚪</span>
                                                    <span>Office: {{ $department->office_room }}</span>
                                                </div>
                                            @endif

                                          
                                        </div>
                                    @endif
                                </a>
                            @endforeach
                        </div>
                    </div>
                </aside>

                {{-- MAIN --}}
                <div class="space-y-8">

                    {{-- FILTER --}}
                    <form class="rounded-3xl border border-gray-200 bg-white p-6 shadow-sm">
                        <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
                            <div class="w-full sm:max-w-sm">
                                <label class="text-xs font-semibold uppercase tracking-wide text-gray-400" for="department">
                                    {{ __('common.labels.department') }}
                                </label>
                                <select
                                    id="department"
                                    name="department"
                                    class="mt-2 w-full rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-sm text-gray-900 shadow-sm focus:border-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-200"
                                >
                                    <option value="">{{ __('common.labels.all') }}</option>
                                    @foreach ($departments as $department)
                                        <option value="{{ $department->slug }}" @selected(request('department') === $department->slug)>
                                            {{ $department->display_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="flex gap-3">
                                <button
                                    type="submit"
                                    class="inline-flex items-center justify-center rounded-xl bg-gray-900 px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-gray-800"
                                >
                                    {{ __('common.actions.filter') }}
                                </button>

                                <a
                                    href="{{ route('staff.index') }}"
                                    class="inline-flex items-center justify-center rounded-xl border border-gray-200 bg-white px-5 py-2.5 text-sm font-semibold text-gray-700 shadow-sm transition hover:border-gray-300 hover:bg-gray-50"
                                >
                                    {{ __('common.actions.reset') ?? 'Reset' }}
                                </a>
                            </div>
                        </div>
                    </form>

                    {{-- STAFF GRID --}}
                    <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                        @forelse ($staff as $member)
                            <a
                                href="{{ route('staff.show', $member) }}"
                                class="group overflow-hidden rounded-3xl border border-gray-200 bg-white shadow-sm transition hover:-translate-y-1 hover:shadow-lg"
                            >
                                <div class="relative h-40 w-full bg-gray-100">
                                    @if ($member->photo_path)
                                        <img
                                            src="{{ asset('storage/' . $member->photo_path) }}"
                                            alt="{{ $member->display_name }}"
                                            class="h-40 w-full object-cover"
                                            loading="lazy"
                                            onerror="this.style.display='none'"
                                        >
                                    @endif

                                    {{-- subtle overlay --}}
                                    <div class="absolute inset-0 bg-gradient-to-t from-black/10 to-transparent opacity-0 transition group-hover:opacity-100"></div>
                                </div>

                                <div class="p-6">
                                    <h3 class="text-lg font-semibold text-gray-900 group-hover:text-blue-700">
                                        {{ $member->display_name }}
                                    </h3>

                                    <p class="mt-1 text-sm text-gray-600">
                                        {{ $member->display_title }}
                                    </p>

                                    @if ($member->department)
                                        <div class="mt-3 inline-flex items-center gap-2 rounded-full bg-gray-100 px-3 py-1 text-xs font-semibold uppercase tracking-wide text-gray-600">
                                            <span class="h-2 w-2 rounded-full bg-blue-500"></span>
                                            {{ $member->department->display_name }}
                                        </div>
                                    @endif
                                </div>
                            </a>
                        @empty
                            <div class="rounded-3xl border border-gray-200 bg-white p-10 text-center text-sm text-gray-500 sm:col-span-2 lg:col-span-3">
                                {{ __('common.messages.no_staff') }}
                            </div>
                        @endforelse
                    </div>

                    {{-- PAGINATION --}}
                    <div class="pt-2">
                        {{ $staff->links() }}
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
