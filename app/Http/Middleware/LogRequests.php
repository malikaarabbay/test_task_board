<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;

/**
 * Middleware LogRequests.
 *
 * Логирует каждый входящий HTTP-запрос: метод, путь, параметры, статус ответа и время обработки.
 * Полезен для мониторинга производительности и отладки API.
 */
class LogRequests
{
    /**
     * Обработка входящего запроса.
     *
     * Логирует следующую информацию:
     * - ID пользователя (если авторизован)
     * - HTTP-метод (`GET`, `POST` и т.д.)
     * - URI (`/api/tasks`)
     * - Все параметры запроса
     * - HTTP-статус ответа
     * - Время выполнения запроса в миллисекундах
     *
     * @param  Request  $request
     * @param  Closure(Request): (Response|RedirectResponse)  $next
     * @return Response|RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $start = microtime(true);

        $response = $next($request);

        $duration = microtime(true) - $start;

        Log::info('API Request', [
            'user_id' => $request->user()?->id,
            'method' => $request->getMethod(),
            'path' => $request->getPathInfo(),
            'params' => $request->all(),
            'status' => $response->getStatusCode(),
            'duration_ms' => $duration * 1000,
        ]);

        return $response;
    }
}
