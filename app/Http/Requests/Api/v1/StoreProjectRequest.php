<?php

namespace App\Http\Requests\Api\v1;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Запрос StoreProjectRequest.
 *
 * Используется для валидации данных при создании нового проекта.
 *
 * Параметры запроса:
 * - `name` (string, required): Название проекта (максимум 255 символов)
 * - `description` (string|null, optional): Описание проекта
 */
class StoreProjectRequest extends FormRequest
{
    /**
     * Проверяет, авторизован ли пользователь для создания проекта.
     *
     * Использует политику `ProjectPolicy@create`.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return $this->user()->can('create', \App\Models\Project::class);
    }

    /**
     * Правила валидации входящего запроса.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ];
    }
}
