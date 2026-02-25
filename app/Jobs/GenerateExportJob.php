<?php

namespace App\Jobs;

use App\Exports\ReportExportExport;
use App\Models\ReportExport;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Excel as ExcelWriter;
use Throwable;

class GenerateExportJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public function __construct(public string $reportExportId)
    {
    }

    public function handle(): void
    {
        $report = ReportExport::find($this->reportExportId);

        if (! $report) {
            return;
        }

        $report->markProcessing();

        Storage::disk('local')->makeDirectory('private/exports');

        try {
            if ($report->format === 'pdf') {
                $path = $this->generatePdf($report);
            } else {
                $path = $this->generateSpreadsheet($report);
            }

            $report->update([
                'file_path' => $path,
                'status' => 'completed',
                'completed_at' => now(),
            ]);
        } catch (Throwable $exception) {
            $report->markFailed($exception->getMessage());
        }
    }

    private function generateSpreadsheet(ReportExport $report): string
    {
        [$query, $headings] = $this->buildQuery($report);

        $extension = $report->format === 'excel' ? 'xlsx' : 'csv';
        $writer = $report->format === 'excel' ? ExcelWriter::XLSX : ExcelWriter::CSV;
        $path = "private/exports/{$report->id}.{$extension}";

        Excel::store(new ReportExportExport($query, $headings), $path, 'local', $writer);

        return $path;
    }

    private function generatePdf(ReportExport $report): string
    {
        [$query, $headings] = $this->buildQuery($report);
        $rows = $query->limit(100)->get();

        $summary = [
            'type' => $report->type,
            'filters' => $report->filters,
            'total' => $rows->count(),
        ];

        $content = Pdf::loadView('admin.exports.pdf', [
            'headings' => $headings,
            'rows' => $rows,
            'summary' => $summary,
        ]);

        $path = "private/exports/{$report->id}.pdf";
        Storage::disk('local')->put($path, $content->output());

        return $path;
    }

    private function buildQuery(ReportExport $report): array
    {
        $filters = $report->filters ?? [];
        $builder = null;
        $headings = [];

        switch ($report->type) {
            case 'tickets':
                $builder = \App\Models\Ticket::query()
                    ->select(['reference_code', 'name', 'email', 'phone', 'subject', 'status', 'created_at']);
                $headings = ['Reference', 'Name', 'Email', 'Phone', 'Subject', 'Status', 'Created At'];
                $this->applyStatusFilter($builder, $filters);
                $this->applyDateRange($builder, $filters, 'created_at');
                break;
            case 'service_requests':
                $builder = \App\Models\ServiceRequest::query()
                    ->select([
                        'service_requests.reference_code',
                        'service_requests.full_name',
                        'service_requests.email',
                        'service_requests.phone',
                        'service_requests.status',
                        'service_requests.submitted_at',
                        'services.title_en as service_title',
                    ])
                    ->leftJoin('services', 'services.id', '=', 'service_requests.service_id');
                $headings = ['Reference', 'Name', 'Email', 'Phone', 'Status', 'Submitted At', 'Service'];
                $this->applyStatusFilter($builder, $filters);
                $this->applyDateRange($builder, $filters, 'submitted_at');
                break;
            case 'appointments':
                $builder = \App\Models\Appointment::query()
                    ->select([
                        'appointments.reference_code',
                        'appointments.full_name',
                        'appointments.email',
                        'appointments.phone',
                        'appointments.status',
                        'appointments.booked_at',
                        'appointment_services.name_en as service_name',
                        'appointment_slots.starts_at as slot_start',
                        'appointment_slots.ends_at as slot_end',
                    ])
                    ->leftJoin('appointment_services', 'appointment_services.id', '=', 'appointments.appointment_service_id')
                    ->leftJoin('appointment_slots', 'appointment_slots.id', '=', 'appointments.appointment_slot_id');
                $headings = ['Reference', 'Name', 'Email', 'Phone', 'Status', 'Booked At', 'Service', 'Slot Start', 'Slot End'];
                $this->applyStatusFilter($builder, $filters);
                $this->applyDateRange($builder, $filters, 'booked_at');
                break;
            case 'vacancy_applications':
                if (! Schema::hasTable('vacancy_applications')) {
                    throw new \RuntimeException('Vacancy applications table is missing.');
                }
                $builder = DB::table('vacancy_applications')
                    ->select([
                        'reference_code',
                        'full_name',
                        'phone',
                        'email',
                        'status',
                        'submitted_at',
                    ]);
                $headings = ['Reference', 'Name', 'Phone', 'Email', 'Status', 'Submitted At'];
                $this->applyStatusFilter($builder, $filters);
                $this->applyDateRange($builder, $filters, 'submitted_at');
                break;
            case 'subscribers':
                if (! Schema::hasTable('subscribers')) {
                    throw new \RuntimeException('Subscribers table is missing.');
                }
                $builder = DB::table('subscribers')
                    ->select(['email', 'phone', 'locale', 'is_active', 'verified_at', 'created_at']);
                $headings = ['Email', 'Phone', 'Locale', 'Active', 'Verified At', 'Created At'];
                $this->applyDateRange($builder, $filters, 'created_at');
                break;
        }

        if (! $builder) {
            throw new \RuntimeException('Invalid export type.');
        }

        return [$builder, $headings];
    }
    private function applyDateRange(QueryBuilder $builder, array $filters, string $column): void
    {
        if (! empty($filters['from'])) {
            $builder->where($column, '>=', $filters['from']);
        }

        if (! empty($filters['to'])) {
            $builder->where($column, '<=', $filters['to']);
        }
    }

    private function applyStatusFilter(QueryBuilder $builder, array $filters): void
    {
        if (! empty($filters['status'])) {
            $builder->where('status', $filters['status']);
        }
    }
}
