<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckTokenExpiration
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $token = $request->user()?->currentAccessToken();

        if ($token) {
            $lastUsed = $token->last_used_at ?? $token->created_at;
            $inactiveMinutes = now()->diffInMinutes($lastUsed);

            if ($inactiveMinutes >= 1) {
                $token->delete();

                return response()->json([
                    'message' => 'Sesión expirada por inactividad',
                ], 401);
            }
        }

        return $next($request);
    }
}
