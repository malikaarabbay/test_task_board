<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Task;

/**
 * Почтовое уведомление об изменении статуса задачи.
 *
 * Отправляется пользователю, когда статус задачи обновлён.
 *
 * @property Task $task Задача, статус которой был изменён
 */
class TaskStatusChangedMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    /**
     * Задача, по которой отправляется письмо.
     *
     * @var Task
     */
    public Task $task;

    /**
     * Создание нового экземпляра сообщения.
     *
     * @param Task $task Задача, статус которой изменён
     */
    public function __construct(Task $task)
    {
        $this->task = $task;
    }

    /**
     * Сборка почтового сообщения.
     *
     * Устанавливает тему письма, шаблон и передаёт в него данные.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Task status updated!')
            ->view('emails.task_status_changed')
            ->with(['task' => $this->task]);
    }
}
