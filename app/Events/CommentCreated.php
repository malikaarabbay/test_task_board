<?php

namespace App\Events;

use App\Models\Comment;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;

/**
 * Событие CommentCreated для моментальной трансляции (broadcast).
 *
 * Отправляется сразу после создания нового комментария к задаче.
 * Используется в real-time интерфейсе (например, через Laravel Echo + Pusher).
 */
class CommentCreated implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Модель комментария, прикреплённая к событию.
     *
     * @var Comment
     */
    public Comment $comment;

    /**
     * Создаёт новое событие с загрузкой задачи.
     *
     * @param Comment $comment Комментарий, только что созданный
     */
    public function __construct(Comment $comment)
    {
        $this->comment = $comment->load('task');
    }

    /**
     * Канал, по которому будет транслироваться событие.
     *
     * @return Channel Канал трансляции
     */
    public function broadcastOn(): Channel
    {
        return new Channel('task-comments');
    }

    /**
     * Название события в трансляции.
     *
     * @return string
     */
    public function broadcastAs(): string
    {
        return 'CommentCreated';
    }

    /**
     * Данные, передаваемые клиентам вместе с событием.
     *
     * @return array
     */
    public function broadcastWith(): array
    {
        return [
            'id' => $this->comment->id,
            'message' => $this->comment->comment,
            'created_at' => $this->comment->created_at->toISOString(),
            'task'       => [
                'title' => $this->comment->task->title,
            ],
        ];
    }
}
