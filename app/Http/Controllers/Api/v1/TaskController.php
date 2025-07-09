<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Requests\Api\v1\IndexTaskRequest;
use \App\Http\Requests\Api\v1\StoreTaskRequest;
use App\Http\Requests\Api\v1\UpdateTaskStatusRequest;
use App\Http\Resources\TaskResource;
use App\Models\Task;
use App\Services\TaskService;
use Illuminate\Support\Facades\Cache;
use Illuminate\Http\JsonResponse;
use Throwable;

/**
 * Контроллер задач.
 *
 * Отвечает за получение, создание и обновление статуса задач.
 */
class TaskController extends ApiController
{
    /**
     * Получение списка задач с фильтрацией и пагинацией.
     *
     * Использует кэш на 60 секунд для ускорения повторных запросов.
     * Фильтрация осуществляется через scope `filterByComplexCriteria`.
     *
     * @param IndexTaskRequest $request Запрос с фильтрами (`status`, `priority`, `project_id`)
     * @return JsonResponse Пагинированный список задач с мета-данными и ссылками
     *
     * @route GET /api/tasks
     */
    public function index(IndexTaskRequest $request): JsonResponse
    {
        try {
            $filters = $request->validated();

            $tasks = Cache::remember(
                'tasks_'.md5(json_encode($filters)).'_page_'.$request->get('page',1),
                60,
                fn() => Task::with(['executors', 'project', 'dependencies'])
                    ->filterByComplexCriteria($filters)
                    ->paginate(50)
            );

            return $this->responseWithPagination($tasks, TaskResource::class);
        } catch (\Throwable $e) {
            return $this->responseWithError('Something went wrong.', 500);
        }
    }

    /**
     * Создание новой задачи.
     *
     * Использует TaskService для обработки бизнес-логики:
     * - привязка исполнителей и зависимостей
     * - логирование в историю
     *
     * @param StoreTaskRequest $request Запрос с данными задачи
     * @param TaskService $taskService Сервис управления задачами
     * @return JsonResponse JSON-ответ с созданной задачей
     *
     * @route POST /api/tasks
     */
    public function store(StoreTaskRequest $request, TaskService $taskService): JsonResponse
    {
        try {
            $task = $taskService->create($request->validated());

            return $this->responseWithData(
                new TaskResource($task),
                201,
                'Task created successfully'
            );
        } catch (Throwable $e) {
            return $this->responseWithError('Failed to create task', 500);
        }
    }

    /**
     * Обновление статуса задачи.
     *
     * При необходимости создаёт комментарий и отправляет уведомление по email.
     * История изменений фиксируется через TaskHistoryService.
     *
     * @param UpdateTaskStatusRequest $request Запрос с полями `status` и `comment`
     * @param Task $task Задача для обновления
     * @param TaskService $taskService Сервис управления задачами
     * @return JsonResponse JSON-ответ с обновлённой задачей
     *
     * @route PATCH /api/tasks/{task}/status
     */
    public function updateStatus(UpdateTaskStatusRequest $request, Task $task, TaskService $taskService): JsonResponse
    {
        try {
            $task = $taskService->updateStatus($task, $request->validated());

            return $this->responseWithData(
                new TaskResource($task),
                200,
                'Status updated successfully'
            );
        } catch (Throwable $e) {
            return $this->responseWithError('Failed to update status', 500);
        }
    }
}
