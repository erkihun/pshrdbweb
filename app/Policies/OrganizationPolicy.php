<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Organization;
use App\Models\User;

class OrganizationPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('manage organizations') || $user->can('manage organization profile');
    }

    public function view(User $user, Organization $organization): bool
    {
        return $this->canAccessOrganization($user, $organization);
    }

    public function create(User $user): bool
    {
        return $user->can('manage organizations');
    }

    public function update(User $user, Organization $organization): bool
    {
        return $this->canAccessOrganization($user, $organization);
    }

    public function delete(User $user, Organization $organization): bool
    {
        return $user->can('manage organizations');
    }

    private function canAccessOrganization(User $user, Organization $organization): bool
    {
        if ($user->can('manage organizations')) {
            return true;
        }

        if (! $user->can('manage organization profile')) {
            return false;
        }

        return (string) $user->organization_id === (string) $organization->id;
    }
}
