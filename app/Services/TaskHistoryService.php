<?php

namespace App\Services;

use App\Models\Task;
use App\Models\TaskHistory;
use Illuminate\Support\Facades\Auth;

class TaskHistoryService
{
    /**
     * Сохраняет запись истории для задачи.
     *
     * @param  Task    $task
     * @param  string  $action
     * @param  string|null  $comment
     * @param  int|null  $userId
     * @return TaskHistory
     */
    public function record(Task $task, string $action, ?string $comment = null, ?int $userId = null): TaskHistory
    {
        return TaskHistory::create([
            'task_id' => $task->id,
            'user_id' => $userId ?? Auth::id(),
            'action' => $action,
            'comment' => $comment,
        ]);
    }
}

