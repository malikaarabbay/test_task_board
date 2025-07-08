<?php

namespace App\Services;

use App\Models\Task;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\TaskStatusChangedMail;

/**
 * Сервис TaskService.
 *
 * Отвечает за бизнес-логику, связанную с управлением задачами:
 * создание, изменение статуса, привязка исполнителей и зависимостей, логирование и отправка уведомлений.
 */
class TaskService
{
    /**
     * Сервис для работы с историей задач.
     *
     * @var TaskHistoryService
     */
    protected TaskHistoryService $historyService;

    /**
     * Сервис для управления комментариями.
     *
     * @var CommentService
     */
    protected CommentService $commentService;

    /**
     * Конструктор класса.
     *
     * Внедряет сервисы для работы с историей задач и комментариями.
     *
     * @param TaskHistoryService $historyService Сервис истории задач
     * @param CommentService $commentService Сервис комментариев
     */
    public function __construct(TaskHistoryService $historyService, CommentService $commentService)
    {
        $this->historyService = $historyService;
        $this->commentService = $commentService;
    }

    /**
     * Создание новой задачи.
     *
     * - Устанавливает текущего пользователя как автора
     * - Привязывает исполнителей и зависимости (если указаны)
     * - Записывает действие в историю
     *
     * @param array $data Данные задачи (`title`, `status`, `priority`, `executors`, `dependencies` и т.д.)
     * @return Task Созданная задача с загруженными связями `executors`, `dependencies`
     */
    public function create(array $data): Task
    {
        $task = Task::create($data + ['creator_id' => Auth::id()]);

        // Синхронизируем зависимости
        $task->executors()->sync($data['executors'] ?? []);
        $task->dependencies()->sync($data['dependencies'] ?? []);

        $this->historyService->record($task, 'tasks.store', 'Task was created');

        return $task->load(['executors', 'dependencies']);
    }

    /**
     * Обновление статуса задачи.
     *
     * - Обновляет статус
     * - Создаёт комментарий, если передан
     * - Записывает историю изменения
     * - Отправляет уведомление по email автору задачи (в очередь)
     *
     * @param Task $task Задача для обновления
     * @param array $data Массив с ключами `status` и (опционально) `comment`
     * @return Task Обновлённая задача
     */
    public function updateStatus(Task $task, array $data): Task
    {
        $task->update(['status' => $data['status']]);

        if (!empty($data['comment'])) {
            $this->commentService->create($task->id, $data['comment']);
        }

        $this->historyService->record($task, 'tasks.update.status', $data['comment'] ?? null);

        Mail::to($task->creator->email)->queue(new TaskStatusChangedMail($task));

        return $task;
    }
}
