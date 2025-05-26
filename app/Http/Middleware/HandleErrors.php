<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class HandleErrors
{
    public function handle(Request $request, Closure $next)
    {
        try {
            $response = $next($request);

            if ($response->status() >= 400) {
                Log::error('HTTP Error', [
                    'status' => $response->status(),
                    'url' => $request->fullUrl(),
                    'method' => $request->method(),
                    'ip' => $request->ip(),
                    'user_id' => auth()->id(),
                ]);
            }

            return $response;
        } catch (\Exception $e) {
            Log::error('Application Error', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
                'url' => $request->fullUrl(),
                'method' => $request->method(),
                'ip' => $request->ip(),
                'user_id' => auth()->id(),
            ]);

            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Произошла ошибка. Пожалуйста, попробуйте позже.',
                ], 500);
            }

            return redirect()->back()
                ->with('error', 'Произошла ошибка. Пожалуйста, попробуйте позже.');
        }
    }
} 