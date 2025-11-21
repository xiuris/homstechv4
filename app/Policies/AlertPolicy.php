<?php

namespace App\Policies;

use App\Models\Alert;
use App\Models\User;
use App\Policies\Concerns\HandlesPermissions;
use Illuminate\Auth\Access\HandlesAuthorization;

class AlertPolicy
{
    use HandlesAuthorization;
    use HandlesPermissions;

    public function viewAny(User $user): bool
    {
        return $this->hasPermission($user, 'manage alerts');
    }

    public function view(User $user, Alert $alert): bool
    {
        return $this->hasPermission($user, 'manage alerts', $alert->company_id);
    }

    public function create(User $user): bool
    {
        return $this->hasPermission($user, 'manage alerts');
    }

    public function update(User $user, Alert $alert): bool
    {
        return $this->hasPermission($user, 'manage alerts', $alert->company_id);
    }

    public function delete(User $user, Alert $alert): bool
    {
        return $this->hasPermission($user, 'manage alerts', $alert->company_id);
    }
}
