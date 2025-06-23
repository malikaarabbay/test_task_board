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

class TaskController extends ApiController
{
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

    public function store(StoreTaskRequest $request, TaskService $taskService)
    {
        $task = $taskService->create($request->validated());

        return $this->responseWithData(new TaskResource($task), 201);
    }

    public function updateStatus(UpdateTaskStatusRequest $request, Task $task, TaskService $taskService)
    {
        $task = $taskService->updateStatus($task, $request->validated());

        return $this->responseWithData(new TaskResource($task));
    }
}
