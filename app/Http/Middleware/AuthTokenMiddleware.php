<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AuthTokenMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $token = $request->bearerToken();
        $authToken = env('AUTH_TOKEN_CTF');

        if ($token !== $authToken) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }
        

        return $next($request);
    }
}
