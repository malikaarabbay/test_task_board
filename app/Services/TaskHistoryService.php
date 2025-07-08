<?php

namespace App\Services;

use App\Models\Task;
use App\Models\TaskHistory;
use Illuminate\Support\Facades\Auth;

/**
 * Сервис TaskHistoryService.
 *
 * Отвечает за создание записей истории действий над задачами.
 */
class TaskHistoryService
{
    /**
     * Сохраняет запись истории для задачи.
     *
     * Используется для логирования действий пользователя (например: создание комментария, изменение статуса и т.п.).
     *
     * @param Task $task Задача, к которой относится история
     * @param string $action Название действия
     * @param string|null $comment Дополнительный комментарий (опционально)
     * @param int|null $userId ID пользователя, совершившего действие (если не указан — используется текущий)
     * @return TaskHistory Созданная запись истории
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

