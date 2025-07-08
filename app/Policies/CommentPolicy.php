<?php

namespace App\Policies;

use App\Models\Comment;
use App\Models\Task;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CommentPolicy
{
    /**
     * Admin, manager и employee может создавать комментарии
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user) : bool
    {
        return $user->hasRole('admin')
            || $user->hasRole('manager')
            || $user->hasRole('employee');
    }
}
