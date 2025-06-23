<?php

namespace App\Http\Requests\Api\v1;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

abstract class BaseRequest extends FormRequest
{
    /**
     * Кастомный ответ при провале авторизации
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
