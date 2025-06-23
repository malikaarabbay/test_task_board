<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Task;

class TaskStatusChangedMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public Task $task;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Task $task)
    {
        $this->task = $task;
    }

    public function build()
    {
        return $this->subject('Task status updated')
            ->view('emails.task_status_changed')
            ->with(['task' => $this->task]);
    }
}
