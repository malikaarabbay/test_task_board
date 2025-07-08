<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Модель Project.
 *
 * Представляет проект, состоящий из задач и созданный пользователем.
 *
 * @property int $id
 * @property string $name Название проекта
 * @property string|null $description Описание проекта
 * @property int $creator_id ID пользователя-создателя
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 *
 * @property-read \App\Models\User $creator Создатель проекта
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Task> $tasks Список задач проекта
 */
class Project extends BaseModel
{
    use HasFactory;

    /**
     * Поля, доступные для массового заполнения.
     *
     * @var array<int, string>
     */
    protected $fillable = ['name', 'description', 'creator_id'];

    /**
     * Создатель проекта.
     *
     * @return BelongsTo<User, Project>
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

    /**
     * Список задач, связанных с этим проектом.
     *
     * @return HasMany<Task>
     */
    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class);
    }
}
