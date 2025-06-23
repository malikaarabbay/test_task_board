<?php

namespace App\Http\Controllers\Api\v1;


use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller as BaseController;

class ApiController extends BaseController
{
    /**
     * Унифицированный JSON-ответ с данными.
     *
     * @param mixed $data
     * @param int $status
     * @param array $additional
     * @return JsonResponse
     */
    protected function responseWithData(mixed $data, int $status = 200, array $additional = []): JsonResponse
    {
        return response()->json(array_merge(['data' => $data], $additional), $status);
    }

    /**
     * Упрощённый ответ без данных.
     */
    protected function responseMessage(string $message = 'OK', int $status = 200): JsonResponse
    {
        return response()->json(['message' => $message], $status);
    }
}
