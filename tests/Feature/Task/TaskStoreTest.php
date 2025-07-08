<?php

namespace Tests\Feature\Task;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Project;

class TaskStoreTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_user_can_create_task()
    {
        $user = User::first();
        $project = Project::first();

        $data = [
            'project_id' => $project->id,
            'title' => 'Test Task',
            'description' => 'Test description',
            'status' => 'open',
            'priority' => 'medium',
            'executors' => [],
        ];

        $response = $this->actingAs($user)->postJson('/api/tasks', $data);

        $response->assertCreated();
        $this->assertDatabaseHas('tasks', ['title' => 'Test Task']);
    }
}
