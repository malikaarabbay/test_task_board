<?php

namespace App\Policies;

use App\Models\User;

class ProjectPolicy
{
    /**
     * Создание проекта разрешено только для admin или manager.
     */
    public function create(User $user): bool
    {
        return $user->hasRole('admin') || $user->hasRole('manager');
    }
}
