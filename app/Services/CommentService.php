<?php

namespace App\Services;

use App\Events\CommentCreated;
use App\Models\Comment;
use App\Models\Task;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

/**
 * Сервис CommentService.
 *
 * Отвечает за бизнес-логику добавления комментариев к задачам и сохранение истории действий.
 */
class CommentService
{
    /**
     * Сервис для работы с историей задач.
     *
     * @var TaskHistoryService
     */
    protected TaskHistoryService $historyService;

    /**
     * Конструктор класса.
     *
     * Внедряет сервис TaskHistoryService для логирования и отслеживания изменений задач.
     *
     * @param TaskHistoryService $historyService Сервис истории задач
     */
    public function __construct(TaskHistoryService $historyService)
    {
        $this->historyService = $historyService;
    }

    /**
     * Добавляет комментарий к задаче.
     *
     * Также записывает это действие в историю задачи через TaskHistoryService.
     *
     * @param int $taskId ID задачи
     * @param string $commentText Текст комментария
     * @return Comment Созданный комментарий
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException Если задача не найдена
     */
    public function create(int $taskId, string $commentText) : Comment
    {
        $task = Task::findOrFail($taskId);

        $comment = $task->comments()->create([
            'user_id' => Auth::id(),
            'comment' => $commentText,
        ]);

        $this->historyService->record(
            $task,
            'comments.store',
            $commentText
        );

        return $comment;
    }
}
