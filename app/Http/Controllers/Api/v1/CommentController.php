<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Requests\Api\v1\StoreCommentRequest;
use App\Http\Resources\CommentResource;
use App\Services\CommentService;
use App\Services\TaskHistoryService;

class CommentController extends ApiController
{
    protected TaskHistoryService $historyService;

    public function __construct(TaskHistoryService $historyService)
    {
        $this->historyService = $historyService;
    }

    public function store(StoreCommentRequest $request, $id, CommentService $commentService)
    {
        $comment = $commentService->create($id, $request->validated()['comment']);

        return $this->responseWithData(new CommentResource($comment));
    }
}
