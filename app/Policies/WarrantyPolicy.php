<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Warranty;
use App\Policies\Concerns\HandlesPermissions;
use Illuminate\Auth\Access\HandlesAuthorization;

class WarrantyPolicy
{
    use HandlesAuthorization;
    use HandlesPermissions;

    public function viewAny(User $user): bool
    {
        return $this->hasPermission($user, 'manage warranties');
    }

    public function view(User $user, Warranty $warranty): bool
    {
        return $this->hasPermission($user, 'manage warranties', $warranty->company_id);
    }

    public function create(User $user): bool
    {
        return $this->hasPermission($user, 'manage warranties');
    }

    public function update(User $user, Warranty $warranty): bool
    {
        return $this->hasPermission($user, 'manage warranties', $warranty->company_id);
    }

    public function delete(User $user, Warranty $warranty): bool
    {
        return $this->hasPermission($user, 'manage warranties', $warranty->company_id);
    }
}
