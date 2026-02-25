<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Actions\OrgStats\CreateOrgSnapshotAction;
use App\Models\OrgStatSnapshot;
use App\Models\Organization;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CreateOrgSnapshotJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public function __construct(
        private Organization $organization,
        private string $periodType,
        private ?int $year,
        private ?int $month,
        private ?int $quarter,
        private ?int $createdBy = null
    ) {}

    public function handle(CreateOrgSnapshotAction $action): OrgStatSnapshot
    {
        return $action->execute(
            $this->organization,
            $this->periodType,
            $this->year,
            $this->month,
            $this->quarter,
            $this->createdBy
        );
    }
}
