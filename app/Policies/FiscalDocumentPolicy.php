<?php

namespace App\Policies;

use App\Models\FiscalDocument;
use App\Models\User;
use App\Policies\Concerns\HandlesPermissions;

class FiscalDocumentPolicy
{
    use HandlesPermissions;

    public function view(User $user, FiscalDocument $document): bool
    {
        return $this->hasPermission($user, 'manage integrations', $document->company_id);
    }

    public function create(User $user): bool
    {
        return $this->hasPermission($user, 'manage integrations', $user->company_id);
    }
}
