<?php

namespace App\Console\Commands;

use App\Models\Applicant;
use App\Models\User;
use App\Models\VacancyApplication;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class MigrateApplicantUsersToApplicants extends Command
{
    protected $signature = 'applicants:migrate-from-users {--delete-users : Delete applicant users after migration if safe}';

    protected $description = 'Migrate applicant users from users table into applicants and link vacancy applications.';

    public function handle(): int
    {
        if (! DB::getSchemaBuilder()->hasColumn('vacancy_applications', 'applicant_id')) {
            $this->error('vacancy_applications.applicant_id column not found. Run migrations first.');
            return self::FAILURE;
        }

        $userIds = VacancyApplication::query()
            ->whereNotNull('user_id')
            ->pluck('user_id')
            ->unique()
            ->values();

        if ($userIds->isEmpty()) {
            $this->info('No applicant users found for migration.');
            return self::SUCCESS;
        }

        $createdApplicants = 0;
        $linkedApplications = 0;

        DB::beginTransaction();

        try {
            foreach (User::whereIn('id', $userIds)->get() as $user) {
                $applicant = Applicant::firstOrCreate(
                    ['email' => $user->email],
                    [
                        'full_name' => $user->name ?? $user->email,
                        'phone' => $user->phone ?? '',
                        'national_id_number' => $user->national_id ?? null,
                        'gender' => $user->gender ?? null,
                        'password' => $user->password,
                    ]
                );

                if ($applicant->wasRecentlyCreated) {
                    $createdApplicants++;
                }

                $linkedApplications += VacancyApplication::where('user_id', $user->id)
                    ->update(['applicant_id' => $applicant->id]);
            }

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            $this->error('Migration failed: ' . $e->getMessage());
            return self::FAILURE;
        }

        if ($this->option('delete-users')) {
            $deleted = User::whereIn('id', $userIds)->delete();
            $this->info("Deleted {$deleted} applicant users from users table.");
        }

        $this->info("Applicants created: {$createdApplicants}");
        $this->info("Applications linked: {$linkedApplications}");

        return self::SUCCESS;
    }
}
