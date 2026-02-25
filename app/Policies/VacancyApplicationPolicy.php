<?php

namespace App\Policies;

use App\Models\Applicant;
use App\Models\User;
use App\Models\VacancyApplication;
use Illuminate\Support\Carbon;

class VacancyApplicationPolicy
{
    public function view(User|Applicant $user, VacancyApplication $application): bool
    {
        if ($user instanceof User) {
            return $this->isAdmin($user);
        }

        return (string) $application->applicant_id === (string) $user->id;
    }

    public function update(User|Applicant $user, VacancyApplication $application): bool
    {
        return $user instanceof User && $this->isAdmin($user);
    }

    public function withdraw(User|Applicant $user, VacancyApplication $application): bool
    {
        if ($user instanceof User) {
            return $this->isAdmin($user);
        }

        return (string) $application->applicant_id === (string) $user->id
            && $application->status === VacancyApplication::STATUS_SUBMITTED
            && $this->isBeforeDeadline($application);
    }

    public function updateProfile(Applicant $user, VacancyApplication $application): bool
    {
        return (string) $application->applicant_id === (string) $user->id
            && $this->isBeforeDeadline($application);
    }

    public function downloadCv(User|Applicant $user, VacancyApplication $application): bool
    {
        return $this->view($user, $application);
    }

    private function isAdmin(User $user): bool
    {
        return $user->can('manage vacancy applications');
    }

    private function isBeforeDeadline(VacancyApplication $application): bool
    {
        $application->loadMissing('vacancy');
        $deadline = $application->vacancy?->deadline;

        return $deadline instanceof Carbon && now()->lte($deadline);
    }
}
