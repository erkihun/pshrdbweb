<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\Department;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    public function overview(Request $request): View
    {
        $user = $request->user();
        $department = $user->department_id ? Department::find($user->department_id) : null;
        $profileDetails = [
            __('common.fields.email') => $user->email,
            __('common.fields.phone') => $user->phone ?? __('common.labels.not_available'),
            __('common.fields.national_id') => $user->national_id ?? __('common.labels.not_available'),
            __('common.fields.gender') => $user->gender ?? __('common.labels.not_available'),
            __('common.fields.department') => $department?->name ?? __('common.labels.not_assigned'),
            __('common.fields.roles') => $user->getRoleNames()->implode(', ') ?: __('common.labels.user'),
            __('common.fields.member_since') => $user->created_at?->timezone('Africa/Addis_Ababa')->format('F d, Y') ?? __('common.labels.unknown'),
            __('common.fields.email_verified') => $user->email_verified_at ? __('common.labels.yes') : __('common.labels.no'),
        ];

        return view('admin.profile.overview', compact('user', 'profileDetails'));
    }

    public function adminEdit(Request $request): View
    {
        return view('admin.profile.update', [
            'user' => $request->user(),
            'departments' => Department::orderBy('name_en')->get(),
        ]);
    }

    public function passwordForm(Request $request): View
    {
        return view('admin.profile.password', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();
        $data = $request->safe()->only(['name', 'email', 'phone', 'national_id', 'gender', 'department_id']);

        $user->fill($data);

        if ($request->hasFile('avatar')) {
            $user->avatar_path = $request->file('avatar')->store('users/avatars', 'public');
        }

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        $redirectRoute = $request->input('redirect_to', 'admin.profile');

        return Redirect::route($redirectRoute)->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
