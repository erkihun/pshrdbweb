@php
    $redirectToAdmin = $redirectToAdmin ?? false;
    $redirectRoute = $redirectRoute ?? 'admin.profile';
    $departments = $departments ?? \App\Models\Department::orderBy('name_en')->get();
    $avatarUrl = $user->avatar_path ? asset('storage/' . $user->avatar_path) : null;
    $formattedNationalId = trim(chunk_split(old('national_id', $user->national_id ?? ''), 4, ' '));
@endphp

<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('admin.profile.information_heading') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __('admin.profile.information_description') }}
        </p>
    </header>

   <form id="send-verification" method="post" action="{{ route('verification.send') }}">
       @csrf
   </form>

    @if($avatarUrl)
        <div class="mb-4 flex items-center gap-3">
            <img src="{{ $avatarUrl }}" alt="{{ $user->name }} avatar" class="h-16 w-16 rounded-full object-cover border border-slate-200 shadow-sm">
            <div>
                <p class="text-sm font-medium text-slate-900">{{ __('admin.profile.current_avatar', ['file' => basename($user->avatar_path)]) }}</p>
                <p class="text-xs text-slate-500">{{ __('admin.profile.avatar_label') }}</p>
            </div>
        </div>
    @endif

    <form method="post" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="mt-6 space-y-6">
        @csrf
        @method('patch')

        <input type="hidden" name="redirect_to" value="{{ $redirectRoute }}">

        <div>
            <x-input-label for="name" :value="__('admin.users.full_name')" />
            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $user->name)" required autofocus autocomplete="name" />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        <div>
            <x-input-label for="email" :value="__('admin.users.email_address')" />
            <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $user->email)" required autocomplete="username" />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div>
                    <p class="text-sm mt-2 text-gray-800">
                        {{ __('admin.profile.email_unverified') }}

                        <button form="send-verification" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            {{ __('admin.profile.email_resend_cta') }}
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 font-medium text-sm text-green-600">
                            {{ __('admin.profile.email_sent_notification') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <div class="grid gap-4 md:grid-cols-2">
            <div>
            <x-input-label for="phone" :value="__('admin.users.phone')" />
                <x-text-input id="phone" name="phone" type="text" class="mt-1 block w-full" :value="old('phone', $user->phone)" autocomplete="tel" />
                <x-input-error class="mt-2" :messages="$errors->get('phone')" />
            </div>

            <div>
            <x-input-label for="national_id" :value="__('admin.users.national_id')" />
            <x-text-input
                id="national_id"
                name="national_id"
                type="text"
                class="mt-1 block w-full national-id-field"
                :value="$formattedNationalId"
                inputmode="numeric"
                maxlength="19"
                pattern="[0-9 ]*"
            />
                <x-input-error class="mt-2" :messages="$errors->get('national_id')" />
            </div>
        </div>

        <div class="grid gap-4 md:grid-cols-2">
            <div>
            <x-input-label for="gender" :value="__('admin.profile.gender_label')" />
                <select
                    id="gender"
                    name="gender"
                    class="mt-1 block w-full rounded-2xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-800 focus:border-brand-blue focus:outline-none"
                >
                    <option value="">{{ __('admin.profile.select_gender') }}</option>
                    <option value="male" {{ old('gender', $user->gender) === 'male' ? 'selected' : '' }}>{{ __('admin.gender_options.male') }}</option>
                    <option value="female" {{ old('gender', $user->gender) === 'female' ? 'selected' : '' }}>{{ __('admin.gender_options.female') }}</option>
                </select>
                <x-input-error class="mt-2" :messages="$errors->get('gender')" />
            </div>

            <div>
            <x-input-label for="department_id" :value="__('admin.profile.department_label')" />
                <select
                    id="department_id"
                    name="department_id"
                    class="mt-1 block w-full rounded-2xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-800 focus:border-brand-blue focus:outline-none"
                >
                    <option value="">{{ __('admin.profile.select_department') }}</option>
                    @foreach($departments as $department)
                        <option value="{{ $department->id }}" {{ (string) old('department_id', $user->department_id) === (string) $department->id ? 'selected' : '' }}>
                            {{ $department->name_en }}
                        </option>
                    @endforeach
                </select>
                <x-input-error class="mt-2" :messages="$errors->get('department_id')" />
            </div>
        </div>

        <div>
            <x-input-label for="avatar" :value="__('admin.profile.avatar_label')" />
            <input
                id="avatar"
                name="avatar"
                type="file"
                class="mt-1 block w-full rounded-2xl border border-slate-200 px-3 py-2 text-sm text-slate-800 focus:border-brand-blue focus:outline-none"
            >
            <x-input-error class="mt-2" :messages="$errors->get('avatar')" />
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('common.actions.save') }}</x-primary-button>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600"
                >{{ __('admin.profile.saved') }}</p>
            @endif
        </div>
    </form>
</section>
