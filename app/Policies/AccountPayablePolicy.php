<?php

namespace App\Policies;

use App\Models\AccountPayable;
use App\Models\User;
use App\Policies\Concerns\HandlesPermissions;
use Illuminate\Auth\Access\HandlesAuthorization;

class AccountPayablePolicy
{
    use HandlesAuthorization;
    use HandlesPermissions;

    public function viewAny(User $user): bool
    {
        return $this->hasPermission($user, 'manage finances');
    }

    public function view(User $user, AccountPayable $accountPayable): bool
    {
        return $this->hasPermission($user, 'manage finances', $accountPayable->company_id);
    }

    public function create(User $user): bool
    {
        return $this->hasPermission($user, 'manage finances');
    }

    public function update(User $user, AccountPayable $accountPayable): bool
    {
        return $this->hasPermission($user, 'manage finances', $accountPayable->company_id);
    }

    public function delete(User $user, AccountPayable $accountPayable): bool
    {
        return $this->hasPermission($user, 'manage finances', $accountPayable->company_id);
    }
}
