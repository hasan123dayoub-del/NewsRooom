<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class LogRequestData
{
    public function handle(Request $request, Closure $next): Response
    {
        $request->attributes->set('start_time', microtime(true));
        return $next($request);
    }

    public function terminate(Request $request, Response $response): void
    {
        $startTime = $request->attributes->get('start_time');
        $duration = $startTime ? round((microtime(true) - $startTime) * 1000, 2) : 0;

        Log::info('API Request Logged', [
            'user_id'     => $request->user()?->id ?? 'Guest',
            'method'      => $request->method(),
            'ip'          => $request->ip(),
            'timestamp'   => now()->toDateTimeString(),
            'duration_ms' => $duration . 'ms',
            'status'      => $response->getStatusCode()
        ]);
    }
}
