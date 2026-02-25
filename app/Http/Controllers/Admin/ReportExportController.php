<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreReportExportRequest;
use App\Jobs\GenerateExportJob;
use App\Models\ReportExport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ReportExportController extends Controller
{
    public function index()
    {
        $exports = ReportExport::with('user')
            ->orderByDesc('requested_at')
            ->paginate(12);

        return view('admin.exports.index', compact('exports'));
    }

    public function create()
    {
        return view('admin.exports.create');
    }

    public function store(StoreReportExportRequest $request)
    {
        $filters = array_filter($request->only(['from', 'to', 'status']));

        $export = ReportExport::create([
            'user_id' => auth()->id(),
            'type' => $request->type,
            'format' => $request->format,
            'filters' => $filters,
        ]);

        GenerateExportJob::dispatch($export->id);

        return redirect()
            ->route('admin.exports.index')
            ->with('success', __('common.messages.export_queued'));
    }

    public function download(Request $request, ReportExport $reportExport)
    {
        if (! $request->hasValidSignature()) {
            abort(403);
        }

        if (! $reportExport->isCompleted() || ! $reportExport->file_path || ! Storage::disk('local')->exists($reportExport->file_path)) {
            return redirect()
                ->route('admin.exports.index')
                ->with('error', __('common.messages.export_not_ready'));
        }

        return Storage::disk('local')->download($reportExport->file_path, $this->filename($reportExport));
    }

    private function filename(ReportExport $reportExport): string
    {
        $extension = pathinfo($reportExport->file_path, PATHINFO_EXTENSION);

        return "{$reportExport->type}-{$reportExport->id}.{$extension}";
    }
}
