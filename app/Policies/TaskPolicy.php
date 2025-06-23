<?php

namespace App\Policies;

use App\Models\Task;
use App\Models\User;

class TaskPolicy
{
    /**
     * Может ли пользователь просматривать задачу?
     * — admin, manager или employee
     */
    public function view(User $user): bool
    {
        return $user->hasRole('admin')
            || $user->hasRole('manager')
            || $user->hasRole('employee');
    }

    /**
     * Может ли пользователь создавать задачи?
     */
    public function create(User $user): bool
    {
        return $user->roles()->whereIn('name', ['admin', 'manager'])->exists();
    }

    /**
     * Может ли пользователь обновлять задачу?
     * — admin или manager
     * — либо employee, если он исполнитель задачи
     */
    public function update(User $user, Task $task): bool
    {
        if ($user->roles()->whereIn('name', ['admin', 'manager'])->exists()) {
            return true;
        }

        return $user->roles()->where('name', 'employee')->exists()
            && $task->executors()->where('users.id', $user->id)->exists();
    }

    /**
     * Может ли пользователь создавать комментарии?
     */
    public function comment(User $user, Task $task): bool
    {
        return $task->creator->id === $user->id || $task->executors()->where('users.id', $user->id)->exists(); // Комментировать могут те, кто видит задачу
    }
}
