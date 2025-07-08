<?php

namespace Tests\Unit\Services;

use Tests\TestCase;
use App\Services\TaskService;
use App\Services\TaskHistoryService;
use App\Services\CommentService;
use App\Mail\TaskStatusChangedMail;
use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class TaskServiceTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_it_updates_task_status_adds_comment_logs_history_and_sends_email()
    {
        Mail::fake();

        $user = User::first();
        Auth::login($user);
        $task = Task::first();

        $historyService = $this->mock(TaskHistoryService::class, function ($mock) {
            $mock->shouldReceive('record')->once();
        });

        $commentService = $this->mock(CommentService::class, function ($mock) {
            $mock->shouldReceive('create')->once();
        });

        $taskService = new TaskService($historyService, $commentService);

        $data = [
            'status' => 'completed',
            'comment' => 'Task is done',
        ];

        $updatedTask = $taskService->updateStatus($task, $data);

        $this->assertEquals('completed', $updatedTask->status);

        Mail::assertQueued(TaskStatusChangedMail::class, function ($mail) use ($task) {
            return $mail->hasTo($task->creator->email);
        });
    }
}
