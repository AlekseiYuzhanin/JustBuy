<?php

namespace App\Http\Middleware;

use Closure;
use JWTAuth;
use Exception;

class ClientRoleMiddleware
{
    public function handle($request, Closure $next, $guard = null)
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();
        } catch (Exception $e) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        if (!$user || $user->role !== 'client') {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        return $next($request);
    }
}