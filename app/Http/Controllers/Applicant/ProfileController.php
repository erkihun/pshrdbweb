<?php

namespace App\Http\Controllers\Applicant;

use App\Http\Controllers\Controller;
use App\Http\Requests\Applicant\UpdateApplicantPasswordRequest;
use App\Http\Requests\Applicant\UpdateApplicantProfileRequest;
use App\Models\Applicant;
use App\Models\VacancyApplication;
use App\Services\AuditLogService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function show(): View
    {
        $applicant = auth('applicant')->user();

        $application = VacancyApplication::where('applicant_id', $applicant->id)
            ->with('vacancy')
            ->latest()
            ->firstOrFail();

        $profilePhotoUrl = ($application->profile_photo_path || $applicant->profile_photo_path)
            ? route('applicant.profile.photo')
            : null;

        return view('applicant.profile.show', [
            'applicant' => $applicant,
            'application' => $application,
            'profilePhotoUrl' => $profilePhotoUrl,
        ]);
    }

    public function update(UpdateApplicantProfileRequest $request): RedirectResponse
    {
        $applicant = auth('applicant')->user();

        $application = VacancyApplication::where('applicant_id', $applicant->id)
            ->with('vacancy')
            ->latest()
            ->firstOrFail();

        $this->authorize('updateProfile', $application);

        [$first, $last] = $this->splitName($request->full_name);

        $applicant->fill([
            'full_name' => $request->full_name,
            'date_of_birth' => $request->date_of_birth,
            'gender' => $request->gender,
            'nationality' => $request->nationality,
            'disability_status' => $request->boolean('disability_status'),
            'education_level' => $request->education_level,
            'field_of_study' => $request->field_of_study,
            'university_name' => $request->university_name,
            'graduation_year' => $request->graduation_year,
            'gpa' => $request->gpa,
            'address' => $request->address,
            'phone' => $request->phone,
            'national_id_number' => $request->national_id_number,
        ]);

        $application->fill([
            'first_name' => $first,
            'last_name' => $last,
            'date_of_birth' => $request->date_of_birth,
            'gender' => $request->gender,
            'disability_status' => $request->boolean('disability_status'),
            'disability_type' => $request->input('disability_type'),
            'education_level' => $request->education_level,
            'field_of_study' => $request->field_of_study,
            'university_name' => $request->university_name,
            'graduation_year' => $request->graduation_year,
            'gpa' => $request->gpa,
            'address' => $request->address,
            'phone' => $request->phone,
            'national_id_number' => $request->national_id_number,
        ]);

        if ($request->hasFile('education_document')) {
            $document = $request->file('education_document');
            $filename = Str::uuid()->toString() . '.' . $document->extension();
            $path = $document->storeAs('private/education-documents', $filename, 'local');

            $disk = Storage::disk('local');
            if ($application->education_document_path) {
                $disk->delete($application->education_document_path);
            }
            if ($application->cv_path) {
                $disk->delete($application->cv_path);
            }

            Storage::disk('public')->delete($application->education_document_path);
            Storage::disk('public')->delete($application->cv_path);

            $application->education_document_path = $path;
            $application->cv_path = $path;
            $application->cv_name = $document->getClientOriginalName();
            $applicant->education_document_path = $path;
        }

        if ($request->hasFile('profile_photo')) {
            $photo = $request->file('profile_photo');
            $filename = Str::uuid()->toString() . '.' . $photo->extension();
            $path = $photo->storeAs('private/profile-photos', $filename, 'local');

            $disk = Storage::disk('local');
            if ($application->profile_photo_path) {
                $disk->delete($application->profile_photo_path);
            }

            Storage::disk('public')->delete($application->profile_photo_path);

            $application->profile_photo_path = $path;
            $applicant->profile_photo_path = $path;
        }

        $application->save();
        $applicant->save();

        AuditLogService::log('vacancies.applications.profile_updated', 'vacancy_application', (string) $application->id, [
            'vacancy_id' => (string) $application->vacancy_id,
        ]);

        return redirect()
            ->route('applicant.profile')
            ->with('status', __('vacancies.public.profile_updated'));
    }

    public function updatePassword(UpdateApplicantPasswordRequest $request): RedirectResponse
    {
        $applicant = auth('applicant')->user();

        if (! Hash::check($request->current_password, $applicant->password)) {
            return back()->withErrors(['current_password' => __('vacancies.public.password_mismatch')]);
        }

        $applicant->password = Hash::make($request->password);
        $applicant->save();

        $request->session()->regenerate();
        $request->session()->regenerateToken();

        AuditLogService::log('vacancies.applications.password_updated', 'applicant', (string) $applicant->id);

        return redirect()
            ->route('applicant.profile')
            ->with('status', __('vacancies.public.password_updated'));
    }

    public function photo(): Response
    {
        $applicant = auth('applicant')->user();
        abort_unless($applicant, 403);

        $path = $applicant->profile_photo_path;
        if (! $path) {
            $application = VacancyApplication::where('applicant_id', $applicant->id)->latest()->first();
            $path = $application?->profile_photo_path;
        }
        abort_unless($path, 404);

        $path = ltrim($path, '/');
        foreach (['storage/', 'app/', 'public/'] as $prefix) {
            if (str_starts_with($path, $prefix)) {
                $path = substr($path, strlen($prefix));
                break;
            }
        }

        $filename = basename($path);
        $candidates = array_values(array_unique([
            $path,
            "private/{$path}",
            "profile-photos/{$filename}",
            "private/profile-photos/{$filename}",
        ]));

        try {
            foreach ($candidates as $candidate) {
                $localAbsolute = storage_path("app/private/{$candidate}");
                if (is_file($localAbsolute)) {
                    return response()->file($localAbsolute);
                }

                $publicAbsolute = storage_path("app/public/{$candidate}");
                if (is_file($publicAbsolute)) {
                    return response()->file($publicAbsolute);
                }
            }

            $disk = Storage::disk('local');
            foreach ($candidates as $candidate) {
                if ($disk->exists($candidate)) {
                    return response()->file($disk->path($candidate));
                }
            }

            $publicDisk = Storage::disk('public');
            foreach ($candidates as $candidate) {
                if ($publicDisk->exists($candidate)) {
                    return response()->file($publicDisk->path($candidate));
                }
            }

            $fallbackPaths = [
                storage_path("app/private/profile-photos/{$filename}"),
                storage_path("app/private/private/profile-photos/{$filename}"),
                storage_path("app/public/profile-photos/{$filename}"),
            ];
            foreach ($fallbackPaths as $fallbackPath) {
                if (is_file($fallbackPath)) {
                    return response()->file($fallbackPath);
                }
            }

            abort(404);
        } catch (\Throwable $e) {
            return response('', 404);
        }
    }

    protected function splitName(string $value): array
    {
        $parts = preg_split('/\s+/', $value, 2, PREG_SPLIT_NO_EMPTY);

        if (! $parts) {
            return [$value, ''];
        }

        return [$parts[0], $parts[1] ?? ''];
    }
}
