<?php

namespace Tests\Unit\Services;

use Tests\TestCase;
use App\Services\CommentService;
use App\Services\TaskHistoryService;
use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;

class CommentServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_creates_comment_and_logs_history()
    {
        $this->seed();

        $user = User::first();
        Auth::login($user);

        $task = Task::first();

        $historyService = $this->mock(TaskHistoryService::class, function ($mock) {
            $mock->shouldReceive('record')->once()
                ->withArgs(function ($task, $action, $comment) {
                    return $action === 'comments.store' && $comment === 'Test comment';
                });
        });

        $service = new CommentService($historyService);

        $comment = $service->create($task->id, 'Test comment');

        $this->assertEquals('Test comment', $comment->comment);
        $this->assertDatabaseHas('comments', ['comment' => 'Test comment']);
    }
}
