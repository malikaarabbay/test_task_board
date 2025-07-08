<?php

namespace App\Http\Requests\Api\v1;

use App\Models\Task;
use App\Rules\CompletedDependencies;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * Запрос UpdateTaskStatusRequest.
 *
 * Используется для обновления статуса задачи с дополнительными условиями и валидацией.
 *
 * Параметры запроса:
 * - `status` (string, required): Новый статус задачи. Допустимые значения:
 *   - `open`
 *   - `in_progress`
 *   - `completed`
 *   - `blocked`
 *   Также проверяется правило бизнес-логики — нельзя установить статус `completed`, если не завершены зависимости.
 *
 * - `comment` (string, required if status = blocked): Комментарий обязателен, если задача блокируется.
 */
class UpdateTaskStatusRequest extends FormRequest
{
    /**
     * Проверка авторизации пользователя на обновление статуса задачи.
     *
     * Использует политику `TaskPolicy@update`.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return $this->user()->can('update', $this->route('task'));
    }

    /**
     * Правила валидации запроса.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'status' => [
                'required',
                Rule::in(['open', 'in_progress', 'completed', 'blocked']),
                new CompletedDependencies($this->route('task')),
            ],
            'comment' => ['required_if:status,blocked', 'string', 'max:500'],
        ];
    }
}
