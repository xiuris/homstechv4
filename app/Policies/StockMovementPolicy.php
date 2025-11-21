<?php

namespace App\Policies;

use App\Models\StockMovement;
use App\Models\User;
use App\Policies\Concerns\HandlesPermissions;
use Illuminate\Auth\Access\HandlesAuthorization;

class StockMovementPolicy
{
    use HandlesAuthorization;
    use HandlesPermissions;

    public function viewAny(User $user): bool
    {
        return $this->hasPermission($user, 'manage stock');
    }

    public function view(User $user, StockMovement $stockMovement): bool
    {
        return $this->hasPermission($user, 'manage stock', $stockMovement->company_id);
    }

    public function create(User $user): bool
    {
        return $this->hasPermission($user, 'manage stock');
    }

    public function update(User $user, StockMovement $stockMovement): bool
    {
        return $this->hasPermission($user, 'manage stock', $stockMovement->company_id);
    }

    public function delete(User $user, StockMovement $stockMovement): bool
    {
        return $this->hasPermission($user, 'manage stock', $stockMovement->company_id);
    }
}
