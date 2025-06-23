<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaskControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Role::factory()->create(['name' => 'admin']);
        Role::factory()->create(['name' => 'manager']);
        Role::factory()->create(['name' => 'employee']);
    }

    public function test_admin_can_create_task(): void
    {
        $user = User::factory()->create();
        $user->roles()->attach(Role::where('name', 'admin')->first()->id);

        $payload = [
            'project_id' => 1,
            'title' => 'Test task',
            'priority' => 'high',
        ];

        $this->actingAs($user)->postJson('/api/tasks', $payload)
            ->assertStatus(201)
            ->assertJsonPath('title', 'Test task');
    }

    public function test_employee_cannot_create_task(): void
    {
        $user = User::factory()->create();
        $user->roles()->attach(Role::where('name', 'employee')->first()->id);

        $this->actingAs($user)->postJson('/api/tasks', [
            'project_id' => 1,
            'title' => 'Blocked',
            'priority' => 'low',
        ])->assertStatus(403); // Forbidden
    }

    public function test_can_update_status(): void
    {
        $user = User::factory()->create();
        $user->roles()->attach(Role::where('name', 'admin')->first()->id);

        $task = Task::factory()->create();

        $this->actingAs($user)->putJson("/api/tasks/{$task->id}/status", [
            'status' => 'completed',
        ])->assertStatus(200)
            ->assertJsonPath('status', 'completed');
    }
}
