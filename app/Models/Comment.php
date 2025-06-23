<?php

namespace App\Models;

use App\Events\CommentCreated;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Comment extends BaseModel
{
    use HasFactory;

    protected $fillable = ['task_id', 'user_id', 'comment'];

    protected static function booted()
    {
        static::created(function (Comment $comment) {
            // Автоматически отправляем событие при создании комментария
            broadcast(new CommentCreated($comment));
        });
    }

    public function task()
    {
        return $this->belongsTo(Task::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
