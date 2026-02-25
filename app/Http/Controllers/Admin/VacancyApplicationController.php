<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateApplicationStatusRequest;
use App\Exports\VacancyApplicationsExport;
use App\Models\Vacancy;
use App\Models\VacancyApplication;
use App\Notifications\ApplicationStatusUpdatedNotification;
use App\Services\AuditLogService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\StreamedResponse;

class VacancyApplicationController extends Controller
{
    public function index(Request $request, ?Vacancy $vacancy = null): View
    {
        $query = VacancyApplication::with('vacancy');

        if ($vacancy) {
            $query->where('vacancy_id', $vacancy->id);
        }

        if ($request->filled('vacancy_id')) {
            $query->where('vacancy_id', $request->vacancy_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($builder) use ($search) {
                $builder->where('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        $applications = $query->orderByDesc('created_at')->paginate(15)->withQueryString();
        $vacancies = Vacancy::orderBy('title')->get();

        return view('admin.vacancies.applications.index', [
            'applications' => $applications,
            'vacancies' => $vacancies,
            'statuses' => VacancyApplication::statuses(),
            'selectedVacancy' => $vacancy?->id ?: $request->vacancy_id,
        ]);
    }

    public function show(VacancyApplication $application): View
    {
        $this->authorize('view', $application);

        $application->load('vacancy', 'applicant');

        AuditLogService::log('vacancies.applications.viewed', 'vacancy_application', (string) $application->id, [
            'vacancy_id' => (string) $application->vacancy_id,
        ]);

        return view('admin.vacancies.applications.show', [
            'application' => $application,
            'statuses' => VacancyApplication::statuses(),
        ]);
    }

    public function update(UpdateApplicationStatusRequest $request, VacancyApplication $application): RedirectResponse
    {
        $this->authorize('update', $application);

        $oldStatus = $application->status;

        $application->update($request->validated());

        if ($oldStatus !== $application->status) {
            $application->load('vacancy', 'applicant');
            AuditLogService::log('vacancies.applications.status_updated', 'vacancy_application', (string) $application->id, [
                'from' => $oldStatus,
                'to' => $application->status,
            ]);
            if ($application->applicant) {
                try {
                    $application->applicant->notify(new ApplicationStatusUpdatedNotification($application));
                } catch (\Throwable $e) {
                    report($e);
                }
            }
        }

        return back()->with('success', __('Vacancy application updated.'));
    }

    public function download(VacancyApplication $application): StreamedResponse|BinaryFileResponse
    {
        $this->authorize('downloadCv', $application);

        $path = $application->cv_path ?: $application->education_document_path;
        abort_unless($path, 404);

        $disk = Storage::disk('local');
        if ($disk->exists($path)) {
            AuditLogService::log('vacancies.applications.cv_downloaded', 'vacancy_application', (string) $application->id, [
                'vacancy_id' => (string) $application->vacancy_id,
            ]);

            return $disk->download($path, $application->cv_name ?? basename($path));
        }

        $publicDisk = Storage::disk('public');
        if ($publicDisk->exists($path)) {
            AuditLogService::log('vacancies.applications.cv_downloaded', 'vacancy_application', (string) $application->id, [
                'vacancy_id' => (string) $application->vacancy_id,
            ]);

            return $publicDisk->download($path, $application->cv_name ?? basename($path));
        }

        abort(404);
    }

    public function document(VacancyApplication $application): Response|\Symfony\Component\HttpFoundation\BinaryFileResponse
    {
        $this->authorize('downloadCv', $application);

        $path = $application->education_document_path ?: $application->cv_path;
        abort_unless($path, 404);

        $disk = Storage::disk('local');
        if ($disk->exists($path)) {
            return response()->file($disk->path($path));
        }

        $publicDisk = Storage::disk('public');
        abort_unless($publicDisk->exists($path), 404);

        return response()->file($publicDisk->path($path));
    }

    public function photo(VacancyApplication $application): Response
    {
        $this->authorize('downloadCv', $application);

        $path = $application->profile_photo_path ?: $application->applicant?->profile_photo_path;
        abort_unless($path, 404);

        $path = ltrim($path, '/');
        $publicPath = str_starts_with($path, 'public/') ? substr($path, 7) : $path;

        $disk = Storage::disk('local');
        if ($disk->exists($path)) {
            return response()->file($disk->path($path));
        }

        $publicDisk = Storage::disk('public');
        abort_unless($publicDisk->exists($publicPath), 404);

        return response()->file($publicDisk->path($publicPath));
    }

    public function destroy(VacancyApplication $application): RedirectResponse
    {
        $disk = Storage::disk('local');
        $disk->delete($application->cv_path);
        if ($application->education_document_path) {
            $disk->delete($application->education_document_path);
        }
        if ($application->profile_photo_path) {
            $disk->delete($application->profile_photo_path);
        }

        $publicDisk = Storage::disk('public');
        $publicDisk->delete($application->cv_path);
        if ($application->education_document_path) {
            $publicDisk->delete($application->education_document_path);
        }
        if ($application->profile_photo_path) {
            $publicDisk->delete($application->profile_photo_path);
        }

        $application->delete();

        return redirect()
            ->route('admin.vacancies.applications.index')
            ->with('success', __('Vacancy application deleted.'));
    }

    public function export(Request $request): BinaryFileResponse
    {
        $validated = $request->validate([
            'vacancy_id' => ['nullable', 'exists:vacancies,id'],
            'status' => ['nullable', 'in:' . implode(',', VacancyApplication::statuses())],
            'from' => ['nullable', 'date'],
            'to' => ['nullable', 'date', 'after_or_equal:from'],
        ]);

        $export = new VacancyApplicationsExport([
            'vacancy_id' => $validated['vacancy_id'] ?? null,
            'status' => $validated['status'] ?? null,
            'from' => $validated['from'] ?? null,
            'to' => $validated['to'] ?? null,
        ]);

        $timestamp = now()->format('Ymd_His');

        return Excel::download($export, "vacancy_applications_{$timestamp}.xlsx");
    }
}
