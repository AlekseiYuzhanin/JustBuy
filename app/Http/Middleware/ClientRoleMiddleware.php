<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class ClientRoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if (!$request->header('Authorization')) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $token = $request->header('Authorization');

        $payload = JWTAuth::getPayload($token)->toArray();

        if ($payload['role'] !== 'client') {
            return response()->json(['error' => 'Access denied'], 403);
        }

        return $next($request);
    }
}
