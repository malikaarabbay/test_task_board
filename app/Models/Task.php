<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Модель Task.
 *
 * Представляет задачу в рамках проекта.
 *
 * @property int $id
 * @property int $project_id ID проекта
 * @property int $creator_id ID пользователя-создателя
 * @property string $title Название задачи
 * @property string|null $description Описание задачи
 * @property string $status Статус задачи (напр. "open", "in_progress", "done")
 * @property string $priority Приоритет задачи (напр. "low", "medium", "high")
 * @property \Illuminate\Support\Carbon|null $deadline Дедлайн задачи
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 *
 * @property-read \App\Models\Project $project Связанный проект
 * @property-read \App\Models\User $creator Автор задачи
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\User> $executors Исполнители задачи
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Task> $dependencies Зависимые задачи
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\TaskHistory> $history История изменений
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Comment> $comments Комментарии к задаче
 */
class Task extends BaseModel
{
    use HasFactory;

    /**
     * Поля, доступные для массового заполнения.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'project_id',
        'creator_id',
        'title',
        'description',
        'status',
        'priority',
        'deadline'
    ];

    /**
     * Кастомный скоуп для фильтрации задач по нескольким критериям.
     *
     * Пример:
     * ```php
     * Task::filterByComplexCriteria([
     *     'status' => 'done',
     *     'priority' => 'high',
     * ])->get();
     * ```
     *
     * @param Builder $query
     * @param array<string, mixed> $filters
     * @return Builder
     */
    public function scopeFilterByComplexCriteria(Builder $query, array $filters): Builder
    {
        return $query
            ->when(!empty($filters['status']), function (Builder $q) use ($filters) {
                $q->where('status', $filters['status']);
            })
            ->when(!empty($filters['priority']), function (Builder $q) use ($filters) {
                $q->where('priority', $filters['priority']);
            })
            ->when(!empty($filters['project_id']), function (Builder $q) use ($filters) {
                $q->where('project_id', $filters['project_id']);
            });
    }

    /**
     * Связь с моделью проекта.
     *
     * @return BelongsTo<Project, Task>
     */
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    /**
     * Автор задачи (создатель).
     *
     * @return BelongsTo<User, Task>
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

    /**
     * Исполнители задачи (многие ко многим).
     *
     * @return BelongsToMany<User>
     */
    public function executors(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'task_user', 'task_id', 'user_id');
    }

    /**
     * Зависимости задачи (другие задачи, от которых зависит текущая).
     *
     * @return BelongsToMany<Task>
     */
    public function dependencies(): BelongsToMany
    {
        return $this->belongsToMany(Task::class, 'task_dependencies', 'task_id', 'depends_on_task_id');
    }

    /**
     * История изменений задачи.
     *
     * @return HasMany<TaskHistory>
     */
    public function history(): HasMany
    {
        return $this->hasMany(TaskHistory::class);
    }

    /**
     * Комментарии к задаче.
     *
     * @return HasMany<Comment>
     */
    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }
}
