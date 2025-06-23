<?php

namespace App\Services;

use App\Events\CommentCreated;
use App\Models\Task;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class CommentService
{
    protected TaskHistoryService $historyService;

    public function __construct(TaskHistoryService $historyService)
    {
        $this->historyService = $historyService;
    }

    public function create(int $taskId, string $commentText)
    {
        $task = Task::findOrFail($taskId);

        $comment = $task->comments()->create([
            'user_id' => Auth::id(),
            'comment' => $commentText,
        ]);

        $this->historyService->record(
            $task,
            'New comment added to the task',
            $commentText
        );

        return $comment;
    }
}
