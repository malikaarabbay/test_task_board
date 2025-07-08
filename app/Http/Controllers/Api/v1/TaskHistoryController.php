<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Resources\TaskHistoryResource;
use App\Models\TaskHistory;
use Illuminate\Http\JsonResponse;

/**
 * Контроллер истории задач.
 *
 * Отвечает за отображение хронологии действий по конкретной задаче.
 */
class TaskHistoryController extends ApiController
{
    /**
     * Получение истории изменений по задаче.
     *
     * Загружает историю с привязкой к пользователю и сортировкой по дате (новые сверху).
     *
     * @param int $id ID задачи, для которой запрашивается история
     * @return JsonResponse JSON-ответ с коллекцией истории
     *
     * @route GET /api/tasks/{id}/history
     * @response 200 {
     *    "data": { ... }
     * }
     */
    public function index($id): JsonResponse
    {
        $history = TaskHistory::with('user')
            ->where('task_id', $id)
            ->orderByDesc('created_at')
            ->get();

        return $this->responseWithData(TaskHistoryResource::collection($history));
    }
}
