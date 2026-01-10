<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Http\Requests\Public\StoreVacancyApplicationRequest;
use App\Models\Vacancy;
use App\Models\VacancyApplication;
use App\Models\Applicant;
use App\Notifications\ApplicationSubmittedNotification;
use App\Services\AuditLogService;
use App\Support\StorageHealth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Illuminate\Database\QueryException;
use Illuminate\Validation\ValidationException;

class VacancyApplicationController extends Controller
{
    public function showApplyForm(string $slug): View|RedirectResponse
    {
        $vacancy = $this->resolveVacancy($slug);

        if ($vacancy->isExpired()) {
            return redirect()
                ->route('vacancies.show', ['slug' => $vacancy->public_slug])
                ->with('error', __('vacancies.public.apply_blocked'));
        }

        $applicant = auth('applicant')->user();
        $hasExistingApplication = $this->applicantHasExistingApplication($applicant);
        if ($hasExistingApplication) {
            $fallback = route('vacancies.show', ['slug' => $vacancy->public_slug]);

            return redirect()->to(url()->previous() ?: $fallback)
                ->with('error', $this->applicationLimitMessage());
        }

        $latestApplication = null;
        if ($applicant) {
            $latestApplication = VacancyApplication::where('applicant_id', $applicant->id)
                ->latest()
                ->first();
        }

        $seoMeta = [
            'title' => __('vacancies.public.apply_title', ['title' => $vacancy->title]),
            'description' => __('vacancies.public.seo_apply_description', ['title' => $vacancy->title]),
            'canonical' => route('vacancies.apply', ['slug' => $vacancy->public_slug]),
        ];

        return view('public.vacancies.apply', [
            'vacancy' => $vacancy,
            'applicant' => $applicant,
            'latestApplication' => $latestApplication,
            'hasExistingApplication' => $hasExistingApplication,
            'seoMeta' => $seoMeta,
        ]);
    }

    public function store(StoreVacancyApplicationRequest $request, string $slug): RedirectResponse
    {
        $vacancy = $this->resolveVacancy($slug);

        if ($vacancy->isExpired()) {
            return redirect()->route('vacancies.show', ['slug' => $vacancy->public_slug])
                ->with('error', __('vacancies.public.apply_blocked'));
        }

        $redirectToApply = $this->applyRedirect($vacancy, $request);

        try {
            $application = DB::transaction(function () use ($request, $vacancy) {
                $phone = $request->phone;
                $authenticatedApplicant = auth('applicant')->user();
                $applicant = $authenticatedApplicant;

                if (! $applicant) {
                    $applicant = Applicant::where('email', $request->email)->first();
                    if ($applicant) {
                        if (! Hash::check($request->password, $applicant->password)) {
                            throw ValidationException::withMessages([
                                'email' => ['Email already registered. Please log in to continue.'],
                            ]);
                        }
                    } else {
                        $applicant = new Applicant([
                            'full_name' => $request->full_name,
                            'email' => $request->email,
                            'password' => Hash::make($request->password),
                        ]);
                    }
                }

                if ($applicant->exists) {
                    Applicant::whereKey($applicant->id)->lockForUpdate()->first();
                }

                $existingApplication = null;
                if ($applicant->exists) {
                    $existingApplication = VacancyApplication::where('applicant_id', $applicant->id)
                        ->lockForUpdate()
                        ->first();
                }

                if ($existingApplication) {
                    throw new \RuntimeException($this->applicationLimitMessage());
                }

                if (! StorageHealth::hasMinimumFreeSpace()) {
                    throw new \RuntimeException('High traffic detected. Please retry in a few minutes.');
                }

                if (VacancyApplication::where('vacancy_id', $vacancy->id)->where('phone', $phone)->exists()) {
                    throw ValidationException::withMessages([
                        'phone' => [__('vacancies.public.duplicate_phone')],
                    ]);
                }

                if (VacancyApplication::where('vacancy_id', $vacancy->id)
                    ->where('national_id_number', $request->national_id_number)
                    ->exists()
                ) {
                    throw ValidationException::withMessages([
                        'national_id_number' => [__('vacancies.public.duplicate_national_id')],
                    ]);
                }

                $educationDocument = $request->file('education_document');
                $profilePhoto = $request->file('profile_photo');

                $latestApplicantApplication = null;
                if ($applicant->exists) {
                    $latestApplicantApplication = VacancyApplication::where('applicant_id', $applicant->id)
                        ->latest()
                        ->first();
                }

                $educationPath = $applicant->education_document_path
                    ?? $latestApplicantApplication?->education_document_path;
                $photoPath = $applicant->profile_photo_path
                    ?? $latestApplicantApplication?->profile_photo_path;

                try {
                    if ($educationDocument) {
                        $educationFilename = Str::uuid()->toString() . '.' . $educationDocument->extension();
                        $educationPath = $educationDocument->storeAs('private/education-documents', $educationFilename, 'local');
                    }

                    if ($profilePhoto) {
                        $photoFilename = Str::uuid()->toString() . '.' . $profilePhoto->extension();
                        $photoPath = $profilePhoto->storeAs('private/profile-photos', $photoFilename, 'local');
                    }
                } catch (\Throwable $exception) {
                    report($exception);
                    throw ValidationException::withMessages([
                        'education_document' => ['Upload failed. Please try again.'],
                    ]);
                }

                if (! $educationPath || ! $photoPath) {
                    throw ValidationException::withMessages([
                        'education_document' => [__('vacancies.public.education_document_hint')],
                        'profile_photo' => [__('vacancies.public.profile_photo_hint')],
                    ]);
                }

        $applicant->fill([
            'full_name' => $request->full_name,
            'phone' => $phone,
            'national_id_number' => $request->national_id_number,
            'gender' => $request->gender,
            'nationality' => $request->nationality,
            'date_of_birth' => $request->date_of_birth,
            'disability_status' => $request->boolean('disability_status'),
            'education_level' => $request->education_level,
                    'field_of_study' => $request->field_of_study,
                    'university_name' => $request->university_name,
                    'graduation_year' => $request->graduation_year,
                    'gpa' => $request->gpa,
                    'education_document_path' => $educationPath,
                    'profile_photo_path' => $photoPath,
                    'address' => $request->address,
                ]);
                $applicant->save();

                [$first, $last] = $this->splitName($request->full_name);

                return VacancyApplication::create([
                    'vacancy_id' => $vacancy->id,
                    'first_name' => $first,
                    'last_name' => $last,
                    'email' => $applicant->email,
                    'phone' => $phone,
                    'cover_letter' => $request->cover_letter,
                    'cv_path' => $educationPath,
                    'cv_name' => $educationDocument?->getClientOriginalName(),
                    'date_of_birth' => $request->date_of_birth,
                    'gender' => $request->gender,
                    'disability_status' => $request->boolean('disability_status'),
                    'disability_type' => $request->input('disability_type'),
                    'education_level' => $request->education_level,
                    'field_of_study' => $request->field_of_study,
                    'university_name' => $request->university_name,
                    'graduation_year' => $request->graduation_year,
                    'gpa' => $request->gpa,
                    'education_document_path' => $educationPath,
                    'profile_photo_path' => $photoPath,
                    'address' => $request->address,
                    'national_id_number' => $request->national_id_number,
                    'applicant_id' => $applicant->id,
                ]);
            }, 3);
        } catch (ValidationException $exception) {
            return $redirectToApply
                ->withInput($request->except(['education_document', 'profile_photo', 'password', 'password_confirmation']))
                ->withErrors($exception->errors());
        } catch (\RuntimeException $exception) {
            return $redirectToApply
                ->withInput($request->except(['education_document', 'profile_photo', 'password', 'password_confirmation']))
                ->with('error', $exception->getMessage());
        } catch (QueryException $exception) {
            if ((string) $exception->getCode() === '23000') {
                return $redirectToApply
                    ->withInput($request->except(['education_document', 'profile_photo', 'password', 'password_confirmation']))
                    ->with('error', $this->applicationLimitMessage());
            }

            report($exception);

            return $redirectToApply
                ->withInput($request->except(['education_document', 'profile_photo', 'password', 'password_confirmation']))
                ->with('error', 'High traffic detected. Please retry in a few minutes.');
        } catch (\Throwable $exception) {
            report($exception);

            return $redirectToApply
                ->withInput($request->except(['education_document', 'profile_photo', 'password', 'password_confirmation']))
                ->with('error', 'High traffic detected. Please retry in a few minutes.');
        }

        $application->load('vacancy');

        AuditLogService::log('vacancies.applications.submitted', 'vacancy_application', (string) $application->id, [
            'vacancy_id' => (string) $vacancy->id,
            'reference' => $application->reference_code,
        ]);

        try {
            $application->applicant?->notify(new ApplicationSubmittedNotification($application));
        } catch (\Throwable $e) {
            report($e);
        }

        return redirect()->route('vacancies.apply.success', ['code' => $application->reference_code]);
    }

    public function success(string $code): View
    {
        $application = VacancyApplication::resolveReferenceCode($code);

        abort_if(! $application, 404);

        $seoMeta = [
            'title' => __('vacancies.public.success_title'),
            'description' => __('vacancies.public.success_message'),
            'canonical' => route('vacancies.apply.success', ['code' => $application->reference_code]),
        ];

        return view('public.vacancies.success', [
            'application' => $application,
            'seoMeta' => $seoMeta,
        ]);
    }

    public function trackForm(): View
    {
        $seoMeta = [
            'title' => __('vacancies.public.track_title'),
            'description' => __('vacancies.public.track_description'),
            'canonical' => route('vacancies.track'),
        ];

        return view('public.vacancies.track', [
            'seoMeta' => $seoMeta,
        ]);
    }

    public function track(Request $request): View
    {
        $request->validate([
            'reference_code' => ['required', 'string'],
        ]);

        $referenceCode = trim(strip_tags((string) $request->input('reference_code')));
        $application = VacancyApplication::resolveReferenceCode($referenceCode);
        if ($application) {
            $application->load('vacancy');
        }

        $seoMeta = [
            'title' => __('vacancies.public.track_title'),
            'description' => __('vacancies.public.track_description'),
            'canonical' => route('vacancies.track'),
        ];

        return view('public.vacancies.track', [
            'seoMeta' => $seoMeta,
            'application' => $application,
            'referenceCode' => strtoupper($referenceCode),
        ]);
    }

    protected function resolveVacancy(string $slug): Vacancy
    {
        return Vacancy::publishedForPublic()
            ->where(function ($query) use ($slug) {
                $query->where('id', $slug)->orWhere('code', $slug);
            })
            ->firstOrFail();
    }

    protected function splitName(string $value): array
    {
        $parts = preg_split('/\s+/', $value, 2, PREG_SPLIT_NO_EMPTY);

        if (! $parts) {
            return [$value, ''];
        }

        return [$parts[0], $parts[1] ?? ''];
    }

    protected function applicantHasExistingApplication(?Applicant $applicant): bool
    {
        if (! $applicant) {
            return false;
        }

        return VacancyApplication::where('applicant_id', $applicant->id)->exists();
    }

    protected function applicationLimitMessage(): string
    {
        return 'You have already applied for a vacancy. Only one application is allowed.';
    }

    protected function applyRedirect(Vacancy $vacancy, Request $request): RedirectResponse
    {
        return redirect()
            ->route('vacancies.apply', ['slug' => $vacancy->public_slug])
            ->withInput($request->except(['education_document', 'profile_photo', 'password', 'password_confirmation']));
    }
}
