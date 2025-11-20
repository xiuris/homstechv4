<?php

namespace App\Policies;

use App\Models\Customer;
use App\Models\User;
use App\Policies\Concerns\HandlesPermissions;
use Illuminate\Auth\Access\HandlesAuthorization;

class CustomerPolicy
{
    use HandlesAuthorization;
    use HandlesPermissions;

    public function viewAny(User $user): bool
    {
        return $this->hasPermission($user, 'manage customers');
    }

    public function view(User $user, Customer $customer): bool
    {
        return $this->hasPermission($user, 'manage customers', $customer->company_id);
    }

    public function create(User $user): bool
    {
        return $this->hasPermission($user, 'manage customers');
    }

    public function update(User $user, Customer $customer): bool
    {
        return $this->hasPermission($user, 'manage customers', $customer->company_id);
    }

    public function delete(User $user, Customer $customer): bool
    {
        return $this->hasPermission($user, 'manage customers', $customer->company_id);
    }
}
