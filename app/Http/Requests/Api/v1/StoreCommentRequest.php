<?php

namespace App\Http\Requests\Api\v1;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Запрос StoreCommentRequest.
 *
 * Используется для валидации данных при создании нового комментария.
 *
 * Параметры запроса:
 * - `comment` (string, required): текст комментария
 */
class StoreCommentRequest extends FormRequest
{
    /**
     * Определяет, авторизован ли пользователь на создание комментария.
     *
     * Проверка производится через политику `create` для модели Comment.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return $this->user()->can('create', \App\Models\Comment::class);
    }

    /**
     * Правила валидации запроса.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'comment' => ['required', 'string'],
        ];
    }
}
