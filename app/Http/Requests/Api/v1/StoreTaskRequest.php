<?php

namespace App\Http\Requests\Api\v1;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * Запрос StoreTaskRequest.
 *
 * Используется для валидации данных при создании новой задачи.
 *
 * Параметры запроса:
 * - `project_id` (int, required): ID проекта, к которому принадлежит задача
 * - `title` (string, required): Название задачи (до 255 символов)
 * - `description` (string|null, optional): Описание задачи
 * - `status` (string|null, optional): Статус задачи (по умолчанию `'open'`; допустимые значения: `open`, `in_progress`, `completed`, `blocked`)
 * - `priority` (string, required): Приоритет задачи (`low`, `medium`, `high`)
 * - `deadline` (string|null, optional): Дата дедлайна (должна быть не раньше сегодняшней)
 * - `executors` (array<int>|null, optional): Список ID пользователей-исполнителей
 * - `executors.*` (int): ID пользователя, должен существовать в таблице `users`
 */
class StoreTaskRequest extends FormRequest
{
    /**
     * Проверка авторизации пользователя на создание задачи.
     *
     * Использует политику `TaskPolicy@create`.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return $this->user()->can('create', \App\Models\Task::class);
    }

    /**
     * Подготовка данных перед валидацией.
     *
     * Устанавливает статус задачи в "open", если он не передан.
     *
     * @return void
     */
    protected function prepareForValidation(): void
    {
        if ($this->missing('status')) {
            $this->merge(['status' => 'open']);
        }
    }

    /**
     * Правила валидации запроса.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'project_id'   => ['required', 'exists:projects,id'],
            'title'        => ['required', 'string', 'max:255'],
            'description'  => ['nullable', 'string'],
            'status'       => ['nullable', Rule::in(['open', 'in_progress', 'completed', 'blocked'])],
            'priority'     => ['required', Rule::in(['low', 'medium', 'high'])],
            'deadline'     => ['nullable', 'date', 'after_or_equal:today'],
            'executors'    => ['nullable', 'array'],
            'executors.*'  => ['exists:users,id'],
        ];
    }
}
