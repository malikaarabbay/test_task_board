<?php

namespace App\Http\Controllers\Api\v1;


use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller as BaseController;

/**
 * Базовый контроллер API.
 *
 * Предоставляет унифицированные методы для формирования JSON-ответов:
 * - ответ с данными (`responseWithData`)
 * - ответ с сообщением (`responseMessage`)
 */
class ApiController extends BaseController
{
    /**
     * Унифицированный JSON-ответ с данными.
     *
     * Возвращает структуру:
     * ```json
     * {
     *   "data": ...,
     *   // + дополнительные поля, если переданы
     * }
     * ```
     *
     * @param mixed $data Данные, которые нужно вернуть клиенту
     * @param int $status HTTP-статус ответа (по умолчанию 200)
     * @param array<string, mixed> $additional Дополнительные поля для ответа (например, `meta`, `pagination`, `message`)
     * @return JsonResponse
     */
    protected function responseWithData(mixed $data, int $status = 200, array $additional = []): JsonResponse
    {
        return response()->json(array_merge(['data' => $data], $additional), $status);
    }

    /**
     * Упрощённый JSON-ответ только с сообщением.
     *
     * Возвращает структуру:
     * ```json
     * {
     *   "message": "OK"
     * }
     * ```
     *
     * @param string $message Текст сообщения
     * @param int $status HTTP-статус ответа (по умолчанию 200)
     * @return JsonResponse
     */
    protected function responseMessage(string $message = 'OK', int $status = 200): JsonResponse
    {
        return response()->json(['message' => $message], $status);
    }
}
