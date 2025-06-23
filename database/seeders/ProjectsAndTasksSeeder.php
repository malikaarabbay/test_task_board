<?php

namespace Database\Seeders;

use App\Models\Comment;
use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProjectsAndTasksSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Найдём менеджеров и сотрудников для привязки
        $managers = User::whereHas('roles', fn($q) => $q->where('name', 'manager'))->get();
        $employees = User::whereHas('roles', fn($q) => $q->where('name', 'employee'))->get();

        foreach ($managers as $manager) {
            $projects = Project::factory(2)->create(['creator_id' => $manager->id]);

            foreach ($projects as $project) {
                $project->tasks()->saveMany(
                    Task::factory(5)->make()->each(function (Task $task) use ($managers) {
                        $task->creator_id = $managers->random()->id;
                    })
                );

                // Добавляем зависимости между задачами
                $tasks = $project->tasks()->get();
                if ($tasks->count() >= 2) {
                    $tasks[0]->dependencies()->attach($tasks[1]->id); // пример зависимости
                }

                // Добавляем комментарии
                foreach ($tasks as $task) {
                    Comment::factory(2)->create([
                        'task_id' => $task->id,
                        'user_id' => $employees->random()->id,
                    ]);
                }
            }
        }
    }
}
