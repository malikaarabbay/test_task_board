<?php

namespace App\Http\Requests\Api\v1;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Запрос IndexTaskRequest.
 *
 * Обрабатывает входящие запросы на получение списка задач с возможностью фильтрации.
 *
 * Параметры запроса:
 * - `status` (string, optional): фильтрация по статусу (`open`, `in_progress`, `blocked`, `completed`)
 * - `priority` (string, optional): фильтрация по приоритету (`low`, `medium`, `high`)
 * - `project_id` (int, optional): фильтрация по проекту (ID должен существовать в таблице `projects`)
 */
class IndexTaskRequest extends FormRequest
{
    /**
     * Определяет, авторизован ли пользователь на выполнение запроса.
     *
     * Проверка производится через политику `view` для модели Task.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return $this->user()->can('view', \App\Models\Task::class);
    }

    /**
     * Правила валидации запроса.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'status' => 'sometimes|string|in:open,in_progress,blocked,completed',
            'priority' => 'sometimes|string|in:low,medium,high',
            'project_id' => 'sometimes|integer|exists:projects,id',
        ];
    }
}
