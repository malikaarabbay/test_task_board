<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Resources\TaskHistoryResource;
use App\Models\TaskHistory;

class TaskHistoryController extends ApiController
{
    public function index($id)
    {
        $history = TaskHistory::with('user')
            ->where('task_id', $id)
            ->orderByDesc('created_at')
            ->get();

        return $this->responseWithData(TaskHistoryResource::collection($history));
    }
}
