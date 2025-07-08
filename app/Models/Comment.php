<?php

namespace App\Models;

use App\Events\CommentCreated;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Модель Comment.
 *
 * Представляет комментарий, оставленный пользователем к задаче.
 *
 * @property int $id
 * @property int $task_id ID связанной задачи
 * @property int $user_id ID пользователя, оставившего комментарий
 * @property string $comment Текст комментария
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 *
 * @property-read \App\Models\Task $task Задача, к которой привязан комментарий
 * @property-read \App\Models\User $user Пользователь, оставивший комментарий
 */
class Comment extends BaseModel
{
    use HasFactory;

    /**
     * Поля, доступные для массового заполнения.
     *
     * @var array<int, string>
     */
    protected $fillable = ['task_id', 'user_id', 'comment'];

    /**
     * Метод booted — обрабатывает события модели.
     *
     * При создании комментария автоматически запускается событие CommentCreated.
     *
     * @return void
     */
    protected static function booted()
    {
        static::created(function (Comment $comment) {
            // Автоматически отправляем событие при создании комментария
            broadcast(new CommentCreated($comment));
        });
    }

    /**
     * Связь с задачей, к которой относится комментарий.
     *
     * @return BelongsTo<Task, Comment>
     */
    public function task(): BelongsTo
    {
        return $this->belongsTo(Task::class);
    }

    /**
     * Связь с пользователем, который оставил комментарий.
     *
     * @return BelongsTo<User, Comment>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
