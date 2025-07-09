<?php

namespace Tests\Feature\Task;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Task;

class TaskIndexTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
        $this->seed(); // Подключаем сидеры
    }

    /**
     * Проверка получения всех задач.
     */
    public function test_can_get_task_list(): void
    {
        $user = User::first();
        $this->actingAs($user);

        // Берём задачу с нужными атрибутами из базы
        $task = Task::where('status', 'open')
            ->where('priority', 'high')
            ->whereHas('project')
            ->first();

        if (!$task) {
            $this->markTestSkipped('Нет задач со статусом open, приоритетом high и привязанным проектом.');
        }

        $filters = [
            'status' => 'open',
            'priority' => 'high',
            'project_id' => $task->project_id,
        ];

        $response = $this->getJson('/api/tasks?' . http_build_query($filters));

        $response->assertOk();

        // Проверка базовой структуры
        $response->assertJsonStructure([
            'success',
            'message',
            'data',
            'links',
            'meta',
        ]);

        // Проверка конкретных значений
        $response->assertJsonPath('data.0.id', $task->id);
        $response->assertJsonPath('data.0.status', 'open');
        $response->assertJsonPath('data.0.priority', 'high');
        $response->assertJsonPath('data.0.project.id', $task->project_id);
    }
}
