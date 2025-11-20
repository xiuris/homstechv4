<?php

namespace App\Policies\Concerns;

use App\Models\User;

trait HandlesPermissions
{
    protected function hasPermission(User $user, string $permission, ?int $companyId = null): bool
    {
        if (! $user->can($permission)) {
            return false;
        }

        if ($companyId !== null && $user->company_id !== $companyId) {
            return false;
        }

        return true;
    }
}
