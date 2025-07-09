<?php

namespace Tests\Feature\Task;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Task;

class TaskStatusUpdateTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_can_update_status_to_completed()
    {
        $user = User::first();
        $task = Task::has('dependencies')->first();

        if (!$task) {
            $this->markTestSkipped('Нет задачи с зависимостями.');
        }

        // Завершаем все зависимости задачи
        foreach ($task->dependencies as $dependency) {
            $dependency->update(['status' => 'completed']);
        }

        $response = $this->actingAs($user)->patchJson("/api/tasks/{$task->id}/status", [
            'status' => 'completed',
        ]);

        $response->assertOk();

        $this->assertDatabaseHas('tasks', [
            'id' => $task->id,
            'status' => 'completed',
        ]);
    }
}
