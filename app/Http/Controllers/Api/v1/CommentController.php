<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Requests\Api\v1\StoreCommentRequest;
use App\Http\Resources\CommentResource;
use App\Services\CommentService;
use App\Services\TaskHistoryService;
use Illuminate\Http\JsonResponse;

/**
 * Контроллер комментариев к задачам.
 *
 * Обрабатывает добавление комментариев и логирование действий.
 */
class CommentController extends ApiController
{
    /**
     * Сервис истории задач.
     *
     * @var TaskHistoryService
     */
    protected TaskHistoryService $historyService;

    /**
     * Конструктор контроллера.
     *
     * @param TaskHistoryService $historyService Сервис для логирования истории задач
     */
    public function __construct(TaskHistoryService $historyService)
    {
        $this->historyService = $historyService;
    }

    /**
     * Добавление комментария к задаче.
     *
     * @param StoreCommentRequest $request Валидированный HTTP-запрос с полем `comment`
     * @param int $id ID задачи, к которой добавляется комментарий
     * @param CommentService $commentService Сервис, реализующий логику добавления комментария
     * @return JsonResponse JSON-ответ с добавленным комментарием
     *
     */
    public function store(StoreCommentRequest $request, $id, CommentService $commentService)
    {
        $comment = $commentService->create($id, $request->validated()['comment']);

        return $this->responseWithData(new CommentResource($comment));
    }
}
