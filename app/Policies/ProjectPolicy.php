<?php

namespace App\Policies;

use App\Models\User;

class ProjectPolicy
{
    /**
     * Создание проекта разрешено только для admin или manager.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user): bool
    {
        return $user->hasRole('admin') || $user->hasRole('manager');
    }
}
