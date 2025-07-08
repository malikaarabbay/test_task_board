<?php

namespace App\Policies;

use App\Models\Task;
use App\Models\User;

class TaskPolicy
{
    /**
     * Может ли пользователь просматривать задачу?
     * — admin, manager или employee
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user): bool
    {
        return $user->hasRole('admin')
            || $user->hasRole('manager')
            || $user->hasRole('employee');
    }

    /**
     * Только admin и manager может создавать задачи
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user): bool
    {
        return $user->roles()->whereIn('name', ['admin', 'manager'])->exists();
    }

    /**
     * Может ли пользователь обновлять задачу?
     * — admin или manager
     * — либо employee, если он исполнитель задачи
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, Task $task): bool
    {
        if ($user->roles()->whereIn('name', ['admin', 'manager'])->exists()) {
            return true;
        }

        return $user->roles()->where('name', 'employee')->exists()
            && $task->executors()->where('users.id', $user->id)->exists();
    }
}
