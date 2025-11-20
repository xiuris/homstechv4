<?php

namespace App\Policies;

use App\Models\AccountReceivable;
use App\Models\User;
use App\Policies\Concerns\HandlesPermissions;
use Illuminate\Auth\Access\HandlesAuthorization;

class AccountReceivablePolicy
{
    use HandlesAuthorization;
    use HandlesPermissions;

    public function viewAny(User $user): bool
    {
        return $this->hasPermission($user, 'manage finances');
    }

    public function view(User $user, AccountReceivable $accountReceivable): bool
    {
        return $this->hasPermission($user, 'manage finances', $accountReceivable->company_id);
    }

    public function create(User $user): bool
    {
        return $this->hasPermission($user, 'manage finances');
    }

    public function update(User $user, AccountReceivable $accountReceivable): bool
    {
        return $this->hasPermission($user, 'manage finances', $accountReceivable->company_id);
    }

    public function delete(User $user, AccountReceivable $accountReceivable): bool
    {
        return $this->hasPermission($user, 'manage finances', $accountReceivable->company_id);
    }
}
