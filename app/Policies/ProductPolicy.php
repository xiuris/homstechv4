<?php

namespace App\Policies;

use App\Models\Product;
use App\Models\User;
use App\Policies\Concerns\HandlesPermissions;
use Illuminate\Auth\Access\HandlesAuthorization;

class ProductPolicy
{
    use HandlesAuthorization;
    use HandlesPermissions;

    public function viewAny(User $user): bool
    {
        return $this->hasPermission($user, 'manage products');
    }

    public function view(User $user, Product $product): bool
    {
        return $this->hasPermission($user, 'manage products', $product->company_id);
    }

    public function create(User $user): bool
    {
        return $this->hasPermission($user, 'manage products');
    }

    public function update(User $user, Product $product): bool
    {
        return $this->hasPermission($user, 'manage products', $product->company_id);
    }

    public function delete(User $user, Product $product): bool
    {
        return $this->hasPermission($user, 'manage products', $product->company_id);
    }
}
