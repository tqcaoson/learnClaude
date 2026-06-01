<?php

namespace App\Policies;

use App\Models\User;

class ReportPolicy
{
    public function create(User $user): bool
    {
        return true;
    }

    public function manage(User $user): bool
    {
        return $user->isModeratorOrAdmin();
    }
}
