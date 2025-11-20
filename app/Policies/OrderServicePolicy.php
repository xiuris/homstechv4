<?php

namespace App\Policies;

use App\Models\OrderService;
use App\Models\User;
use App\Policies\Concerns\HandlesPermissions;
use Illuminate\Auth\Access\HandlesAuthorization;

class OrderServicePolicy
{
    use HandlesAuthorization;
    use HandlesPermissions;

    public function viewAny(User $user): bool
    {
        return $this->hasPermission($user, 'manage order services');
    }

    public function view(User $user, OrderService $orderService): bool
    {
        return $this->hasPermission($user, 'manage order services', $orderService->company_id);
    }

    public function create(User $user): bool
    {
        return $this->hasPermission($user, 'manage order services');
    }

    public function update(User $user, OrderService $orderService): bool
    {
        return $this->hasPermission($user, 'manage order services', $orderService->company_id);
    }

    public function delete(User $user, OrderService $orderService): bool
    {
        return $this->hasPermission($user, 'manage order services', $orderService->company_id);
    }
}
