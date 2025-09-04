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
        // Si ya viene con header Authorization no hacemos nada
        if (! $request->bearerToken()) {
            $token = $request->cookie('auth_token');
            if ($token) {
                $request->headers->set('Authorization', 'Bearer ' . $token);
            }
        }


        return $next($request);
    }
}
