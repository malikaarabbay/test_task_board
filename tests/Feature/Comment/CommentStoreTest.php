<?php

namespace Tests\Feature\Comment;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Task;

class CommentStoreTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_can_add_comment_to_task()
    {
        $user = User::first();
        $task = Task::first();

        $response = $this->actingAs($user)->postJson("/api/tasks/{$task->id}/comments", [
            'comment' => 'This is a test comment',
        ]);

        $response->assertOk();
        $this->assertDatabaseHas('comments', [
            'task_id' => $task->id,
            'comment' => 'This is a test comment',
        ]);
    }
}
