<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Laravel\Sanctum\PersonalAccessToken;
use Carbon\Carbon;

class CheckTokenExpiration
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $tokenValue = $request->cookie('auth_token');
        $token = PersonalAccessToken::findToken($tokenValue);

        if (! $token) {
            return response()->json(['message' => 'Sesión expirada'], 401);
        }

        $lastUsed = $token->last_used_at ?? $token->created_at;
        $inactiveMinutes = now()->diffInMinutes($lastUsed);

        if ($inactiveMinutes >= 2) { // 2 min inactividad
            $token->delete();

            return response()->json(['message' => 'Sesión expirada por inactividad'], 401)
                ->cookie('auth_token', '', -1); // borrar cookie
        }

        // renovar cookie (reinicia contador de inactividad)
        $response = $next($request);
        return $response->cookie(
            'auth_token',
            $tokenValue,
            2, // renovar 2 min
            '/',
            null,
            true,
            true
        );
    }
}
