<?php

namespace App\Policies;

use App\Models\Sale;
use App\Models\User;
use App\Policies\Concerns\HandlesPermissions;
use Illuminate\Auth\Access\HandlesAuthorization;

class SalePolicy
{
    use HandlesAuthorization;
    use HandlesPermissions;

    public function viewAny(User $user): bool
    {
        return $this->hasPermission($user, 'manage sales');
    }

    public function view(User $user, Sale $sale): bool
    {
        return $this->hasPermission($user, 'manage sales', $sale->company_id);
    }

    public function create(User $user): bool
    {
        return $this->hasPermission($user, 'manage sales');
    }

    public function update(User $user, Sale $sale): bool
    {
        return $this->hasPermission($user, 'manage sales', $sale->company_id);
    }

    public function delete(User $user, Sale $sale): bool
    {
        return $this->hasPermission($user, 'manage sales', $sale->company_id);
    }
}
