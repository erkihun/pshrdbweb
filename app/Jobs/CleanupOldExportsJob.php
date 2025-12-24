<?php

namespace App\Jobs;

use App\Models\ReportExport;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

class CleanupOldExportsJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public function handle(): void
    {
        $threshold = Carbon::now()->subDays(30);

        ReportExport::where('requested_at', '<', $threshold)
            ->chunkById(50, function ($exports) {
                foreach ($exports as $export) {
                    if ($export->file_path && Storage::disk('local')->exists($export->file_path)) {
                        Storage::disk('local')->delete($export->file_path);
                    }

                    $export->delete();
                }
            });
    }
}
