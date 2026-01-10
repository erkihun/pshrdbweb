<?php

namespace App\Http\Controllers\Applicant;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\VacancyApplication;
use App\Notifications\ApplicationWithdrawnNotification;
use App\Services\AuditLogService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $user = auth('applicant')->user();

        $applicationsQuery = VacancyApplication::query()
            ->where('applicant_id', $user->id)
            ->with('vacancy')
            ->latest();

        $applications = $applicationsQuery->paginate(10);

        if ($applications->isEmpty() && $applications->currentPage() > 1) {
            return redirect()->route('applicant.dashboard');
        }

        $summary = [
            'total' => VacancyApplication::where('applicant_id', $user->id)->count(),
            'submitted' => VacancyApplication::where('applicant_id', $user->id)->where('status', VacancyApplication::STATUS_SUBMITTED)->count(),
            'shortlisted' => VacancyApplication::where('applicant_id', $user->id)->where('status', VacancyApplication::STATUS_SHORTLISTED)->count(),
            'rejected' => VacancyApplication::where('applicant_id', $user->id)->where('status', VacancyApplication::STATUS_REJECTED)->count(),
            'hired' => VacancyApplication::where('applicant_id', $user->id)->where('status', VacancyApplication::STATUS_HIRED)->count(),
        ];

        return view('applicant.dashboard', [
            'user' => $user,
            'applications' => $applications,
            'summary' => $summary,
        ]);
    }

    public function show(VacancyApplication $application): View
    {
        $this->authorize('view', $application);

        $application->load('vacancy');

        return view('applicant.applications.show', [
            'application' => $application,
        ]);
    }

    public function download(VacancyApplication $application)
    {
        $this->authorize('downloadCv', $application);

        $path = $application->education_document_path ?: $application->cv_path;

        abort_unless($path, 404);

        $disk = Storage::disk('local');
        if ($disk->exists($path)) {
            AuditLogService::log('vacancies.applications.cv_downloaded.applicant', 'vacancy_application', (string) $application->id, [
                'vacancy_id' => (string) $application->vacancy_id,
            ]);

            return $disk->download($path, $application->cv_name ?: basename($path));
        }

        $publicDisk = Storage::disk('public');
        abort_unless($publicDisk->exists($path), 404);

        $filename = $application->cv_name ?: basename($path);

        AuditLogService::log('vacancies.applications.cv_downloaded.applicant', 'vacancy_application', (string) $application->id, [
            'vacancy_id' => (string) $application->vacancy_id,
        ]);

        return $publicDisk->download($path, $filename);
    }

    public function withdraw(VacancyApplication $application): RedirectResponse
    {
        $this->authorize('withdraw', $application);

        DB::transaction(function () use ($application) {
            $application->refresh();
            $application->status = VacancyApplication::STATUS_WITHDRAWN;
            $application->save();
        });

        $application->load('vacancy', 'applicant');

        AuditLogService::log('vacancies.applications.withdrawn', 'vacancy_application', (string) $application->id, [
            'vacancy_id' => (string) $application->vacancy_id,
        ]);

        $admins = User::permission('manage vacancy applications')->get();
        if ($admins->isNotEmpty()) {
            try {
                Notification::send($admins, new ApplicationWithdrawnNotification($application, false));
            } catch (\Throwable $e) {
                report($e);
            }
        }

        if ($application->applicant) {
            try {
                $application->applicant->notify(new ApplicationWithdrawnNotification($application, true));
            } catch (\Throwable $e) {
                report($e);
            }
        }

        return redirect()
            ->route('applicant.dashboard')
            ->with('status', __('vacancies.public.withdrawn'));
    }
}
