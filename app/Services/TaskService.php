<?php

namespace App\Services;

use App\Models\Task;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\TaskStatusChangedMail;

class TaskService
{
    protected TaskHistoryService $historyService;
    protected CommentService $commentService;

    public function __construct(TaskHistoryService $historyService, CommentService $commentService)
    {
        $this->historyService = $historyService;
        $this->commentService = $commentService;
    }

    public function create(array $data): Task
    {
        // Создаём задачу
        $task = Task::create($data + ['creator_id' => Auth::id()]);

        // Синхронизируем зависимости
        $task->executors()->sync($data['executors'] ?? []);
        $task->dependencies()->sync($data['dependencies'] ?? []);

        // Записываем историю
        $this->historyService->record($task, 'created', 'Task was created');

        return $task->load(['executors', 'dependencies']);
    }

    public function updateStatus(Task $task, array $data): Task
    {
        $task->update(['status' => $data['status']]);

        // Если передан комментарий — создаём комментарий
        if (!empty($data['comment'])) {
            $this->commentService->create($task->id, $data['comment']);
        }

        $this->historyService->record($task, 'The task status was updated', $data['comment'] ?? null);

        // Отправка уведомления по email с постановкой в очередь
        Mail::to($task->creator->email)
        ->queue(new TaskStatusChangedMail($task));

        return $task;
    }

}
