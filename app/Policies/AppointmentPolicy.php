<?php

namespace App\Policies;

use App\Models\Appointment;
use App\Models\User;
use App\Policies\Concerns\HandlesPermissions;
use Illuminate\Auth\Access\HandlesAuthorization;

class AppointmentPolicy
{
    use HandlesAuthorization;
    use HandlesPermissions;

    public function viewAny(User $user): bool
    {
        return $this->hasPermission($user, 'manage scheduling');
    }

    public function view(User $user, Appointment $appointment): bool
    {
        return $this->hasPermission($user, 'manage scheduling', $appointment->company_id);
    }

    public function create(User $user): bool
    {
        return $this->hasPermission($user, 'manage scheduling');
    }

    public function update(User $user, Appointment $appointment): bool
    {
        return $this->hasPermission($user, 'manage scheduling', $appointment->company_id);
    }

    public function delete(User $user, Appointment $appointment): bool
    {
        return $this->hasPermission($user, 'manage scheduling', $appointment->company_id);
    }
}
