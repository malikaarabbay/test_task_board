<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Requests\Api\v1\IndexTaskRequest;
use \App\Http\Requests\Api\v1\StoreTaskRequest;
use App\Http\Requests\Api\v1\UpdateTaskStatusRequest;
use App\Http\Resources\TaskResource;
use App\Models\Task;
use App\Services\TaskService;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\JsonResponse;

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
    public function index(IndexTaskRequest $request)
    {
        $filters = $request->validated();

        $tasks = Cache::remember(
            'tasks_'.md5(json_encode($filters)).'_page_'.$request->get('page',1),
            60,
            fn() => Task::with(['executors', 'project', 'dependencies'])
                ->filterByComplexCriteria($filters)
                ->paginate(50)
        );

        return $this->responseWithData(
            TaskResource::collection($tasks),
            200,
            [
                'links' => [
                    'first' => $tasks->url(1),
                    'last' => $tasks->url($tasks->lastPage()),
                    'prev' => $tasks->previousPageUrl(),
                    'next' => $tasks->nextPageUrl(),
                ],
                'meta' => [
                    'current_page' => $tasks->currentPage(),
                    'from' => $tasks->firstItem(),
                    'last_page' => $tasks->lastPage(),
                    'path' => $tasks->path(),
                    'per_page' => $tasks->perPage(),
                    'to' => $tasks->lastItem(),
                    'total' => $tasks->total(),
                ],
            ]
        );
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
     * @response 201 {
     *   "data": { ... }
     * }
     */
    public function store(StoreTaskRequest $request, TaskService $taskService)
    {
        $task = $taskService->create($request->validated());

        return $this->responseWithData(new TaskResource($task), 201);
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
     * @response 200 {
     *   "data": { ... }
     * }
     */
    public function updateStatus(UpdateTaskStatusRequest $request, Task $task, TaskService $taskService)
    {
        $task = $taskService->updateStatus($task, $request->validated());

        return $this->responseWithData(new TaskResource($task));
    }
}
