<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class LogRequests
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
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
