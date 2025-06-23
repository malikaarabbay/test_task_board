<?php

namespace Tests\Unit;

use App\Mail\TaskStatusChangedMail;
use App\Models\Task;
use App\Services\CommentService;
use App\Services\TaskHistoryService;
use App\Services\TaskService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Mockery;
use Tests\TestCase;

class TaskServiceTest extends TestCase
{
    use RefreshDatabase;

    protected $historyServiceMock;
    protected $commentServiceMock;
    protected TaskService $taskService;

    protected function setUp(): void
    {
        parent::setUp();

        $this->historyServiceMock = Mockery::mock(TaskHistoryService::class);
        $this->commentServiceMock = Mockery::mock(CommentService::class);

        $this->taskService = new TaskService(
            $this->historyServiceMock,
            $this->commentServiceMock
        );
    }

    public function test_update_status_updates_status_and_records_history()
    {
        $task = Task::factory()->create(['status' => 'open']);

        $data = ['status' => 'completed', 'comment' => 'Done'];

        $this->commentServiceMock->shouldReceive('create')
            ->once()
            ->with($task->id, 'Done');

        $this->historyServiceMock->shouldReceive('record')
            ->once()
            ->with($task, 'update_status', 'Done');

        Mail::fake();

        $updatedTask = $this->taskService->updateStatus($task, $data);

        $this->assertEquals('completed', $updatedTask->status);

        Mail::assertQueued(TaskStatusChangedMail::class, function ($mail) use ($task) {
            return $mail->task->id === $task->id;
        });
    }

    public function test_update_status_without_comment_records_history_and_sends_mail()
    {
        $task = Task::factory()->create(['status' => 'open']);
        $data = ['status' => 'in_progress'];

        $this->commentServiceMock->shouldNotReceive('create');

        $this->historyServiceMock->shouldReceive('record')
            ->once()
            ->with($task, 'update_status', null);

        Mail::fake();

        $updatedTask = $this->taskService->updateStatus($task, $data);

        $this->assertEquals('in_progress', $updatedTask->status);

        Mail::assertQueued(TaskStatusChangedMail::class);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}
