<?php

namespace App\Policies;

use App\Models\Service;
use App\Models\User;
use App\Policies\Concerns\HandlesPermissions;
use Illuminate\Auth\Access\HandlesAuthorization;

class ServicePolicy
{
    use HandlesAuthorization;
    use HandlesPermissions;

    public function viewAny(User $user): bool
    {
        return $this->hasPermission($user, 'manage services');
    }

    public function view(User $user, Service $service): bool
    {
        return $this->hasPermission($user, 'manage services', $service->company_id);
    }

    public function create(User $user): bool
    {
        return $this->hasPermission($user, 'manage services');
    }

    public function update(User $user, Service $service): bool
    {
        return $this->hasPermission($user, 'manage services', $service->company_id);
    }

    public function delete(User $user, Service $service): bool
    {
        return $this->hasPermission($user, 'manage services', $service->company_id);
    }
}
