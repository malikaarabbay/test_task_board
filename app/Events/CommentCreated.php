<?php

namespace App\Events;

use App\Models\Comment;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;

class CommentCreated implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public Comment $comment;

    public function __construct(Comment $comment)
    {
        $this->comment = $comment->load('task');
    }

    public function broadcastOn() : Channel
    {
        return new Channel('task-comments');
    }

    public function broadcastAs()
    {
        return 'CommentCreated';
    }

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
