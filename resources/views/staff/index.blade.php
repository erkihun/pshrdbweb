<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('common.labels.staff') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            <div class="flex flex-col gap-8">
                <form class="flex flex-wrap items-end gap-4 rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
                    <div class="min-w-[220px]">
                        <label class="text-xs font-semibold uppercase tracking-wide text-gray-400" for="department">{{ __('common.labels.department') }}</label>
                        <select
                            id="department"
                            name="department"
                            class="mt-2 w-full rounded-lg border border-gray-200 px-4 py-2 text-sm focus:border-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-200"
                        >
                            <option value="">{{ __('common.labels.all') }}</option>
                            @foreach ($departments as $department)
                                <option value="{{ $department->slug }}" @selected(request('department') === $department->slug)>
                                    {{ $department->display_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <button
                            type="submit"
                            class="inline-flex items-center rounded-lg bg-gray-900 px-4 py-2 text-sm font-semibold text-white transition hover:bg-gray-800"
                        >
                            {{ __('common.actions.filter') }}
                        </button>
                    </div>
                </form>

                <div class="grid gap-6 md:grid-cols-3">
                    @forelse ($staff as $member)
                        <a
                            href="{{ route('staff.show', $member) }}"
                            class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm transition hover:-translate-y-1"
                        >
                            @if ($member->photo_path)
                                <img
                                    src="{{ asset('storage/' . $member->photo_path) }}"
                                    alt="{{ $member->display_name }}"
                                    class="h-40 w-full rounded-xl object-cover"
                                >
                            @endif
                            <h3 class="mt-4 text-lg font-semibold text-gray-900">{{ $member->display_name }}</h3>
                            <p class="text-sm text-gray-600">{{ $member->display_title }}</p>
                            @if ($member->department)
                                <p class="mt-2 text-xs uppercase tracking-wide text-gray-400">{{ $member->department->display_name }}</p>
                            @endif
                        </a>
                    @empty
                        <div class="rounded-2xl border border-gray-200 bg-white p-8 text-center text-sm text-gray-500 md:col-span-3">
                            {{ __('common.messages.no_staff') }}
                        </div>
                    @endforelse
                </div>

                <div>
                    {{ $staff->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
