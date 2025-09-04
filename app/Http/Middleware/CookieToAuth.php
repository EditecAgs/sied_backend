<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Laravel\Sanctum\PersonalAccessToken;


class CookieToAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $tokenValue = $request->cookie('auth_token');

        if ($tokenValue) {
            $token = PersonalAccessToken::findToken($tokenValue);

            if ($token && $token->tokenable) {
                auth()->setUser($token->tokenable);
            }
        }

        return $next($request);
    }
}
