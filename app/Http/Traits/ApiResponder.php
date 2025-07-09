<?php

namespace App\Http\Traits;

use Illuminate\Http\JsonResponse;
use Illuminate\Pagination\LengthAwarePaginator;

trait ApiResponder
{
    /**
     * Ответ с данными.
     */
    protected function responseWithData(mixed $data, int $status = 200, string $message = 'Success', array $additional = []): JsonResponse
    {
        return response()->json(array_merge([
            'success' => true,
            'message' => $message,
            'data'    => $data,
        ], $additional), $status);
    }

    /**
     * Ответ с сообщением.
     */
    protected function responseMessage(string $message = 'OK', int $status = 200): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => $message,
        ], $status);
    }

    /**
     * Ответ с ошибкой.
     */
    protected function responseWithError(string $message, int $status = 500, array $details = []): JsonResponse
    {
        return response()->json([
            'success' => false,
            'message' => $message,
            'errors'  => $details,
        ], $status);
    }

    /**
     * Ответ с пагинацией.
     */
    protected function responseWithPagination(LengthAwarePaginator $paginator, ?string $resourceClass = null, string $message = 'Success'): JsonResponse
    {
        $data = $resourceClass
            ? $resourceClass::collection($paginator)->resolve()
            : $paginator->items();

        return response()->json([
            'success' => true,
            'message' => $message,
            'data'    => $data,
            'links'   => [
                'first' => $paginator->url(1),
                'last'  => $paginator->url($paginator->lastPage()),
                'prev'  => $paginator->previousPageUrl(),
                'next'  => $paginator->nextPageUrl(),
            ],
            'meta' => [
                'current_page' => $paginator->currentPage(),
                'from'         => $paginator->firstItem(),
                'last_page'    => $paginator->lastPage(),
                'path'         => $paginator->path(),
                'per_page'     => $paginator->perPage(),
                'to'           => $paginator->lastItem(),
                'total'        => $paginator->total(),
            ],
        ]);
    }
}
