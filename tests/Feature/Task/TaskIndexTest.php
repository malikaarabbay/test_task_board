<?php

namespace Tests\Feature\Task;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TaskIndexTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
        $this->seed(); // Подключаем сидеры
    }

    public function test_can_get_task_list()
    {
        $user = \App\Models\User::first(); // авторизация
        $response = $this->actingAs($user)->getJson('/api/tasks');

        $response->assertOk();
        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'id', 'title', 'status', 'priority', // и т.п.
                ]
            ],
            'links',
            'meta'
        ]);
    }
}
