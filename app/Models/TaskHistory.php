<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Модель TaskHistory.
 *
 * Хранит информацию об изменениях задач, произведённых пользователями.
 *
 * @property int $id
 * @property int $task_id ID задачи
 * @property int $user_id ID пользователя
 * @property string $action Тип действия (например, 'created', 'updated', 'deleted')
 * @property string|null $comment Комментарий к действию (если есть)
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 *
 * @property-read \App\Models\Task $task Связанная задача
 * @property-read \App\Models\User $user Пользователь
 */
class TaskHistory extends BaseModel
{
    use HasFactory;

    /**
     * Массово присваиваемые атрибуты.
     *
     * @var array<int, string>
     */
    protected $fillable = ['task_id', 'user_id', 'action', 'comment'];

    /**
     * Получить связанную задачу.
     *
     * @return BelongsTo<Task, TaskHistory>
     */
    public function task(): BelongsTo
    {
        return $this->belongsTo(Task::class);
    }

    /**
     * Получить пользователя.
     *
     * @return BelongsTo<User, TaskHistory>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
