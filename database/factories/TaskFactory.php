<?php

namespace Database\Factories;

use App\Models\Project;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Task>
 */
class TaskFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'project_id' => Project::inRandomOrder()->first()->id ?? Project::factory(),
            'creator_id' => User::inRandomOrder()->first()->id ?? User::factory(),
            'title' => $this->faker->sentence(5),
            'description' => $this->faker->paragraph(),
            'status' => $this->faker->randomElement(['open', 'in_progress']),
            'priority' => $this->faker->randomElement(['low', 'medium', 'high']),
            'deadline' => $this->faker->dateTimeBetween('+1 day', '+2 months'),
        ];
    }
}
