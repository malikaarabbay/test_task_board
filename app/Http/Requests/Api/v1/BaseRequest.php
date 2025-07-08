<?php

namespace App\Http\Requests\Api\v1;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

/**
 * Абстрактный класс BaseRequest.
 *
 * Базовый класс для всех HTTP-запросов, добавляющий кастомную обработку ошибок авторизации.
 * При отказе в авторизации возвращается JSON-ответ с кодом 403.
 */
abstract class BaseRequest extends FormRequest
{
    /**
     * Обработка отказа в авторизации.
     *
     * Переопределяет стандартный редирект и возвращает JSON-ответ с HTTP-статусом 403.
     *
     * @throws HttpResponseException
     */
    protected function failedAuthorization(): void
    {
        throw new HttpResponseException(
            response()->json(
                ['message' => 'You do not have permission to perform this action.'],
                403
            )
        );
    }
}
