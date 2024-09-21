<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Http\Middleware\BaseMiddleware;

class JwtMiddleware extends BaseMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        try {
            JWTAuth::parseToken()->authenticate();
        } catch (\Exception $e) {
            if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenInvalidException) {
                return response()->json([
                    'error' => 'Token is Invalid',
                    'type' => 'error',
                    'message' => 'A credencial atual é invalida.'
                ], 400);
            } else if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenExpiredException) {
                return response()->json([
                    'error' => 'Token is Expired',
                    'type' => 'error',
                    'message' => 'A credencial atual esta expirada, realize login novamente.'
                ], 400);
            } else {
                return response()->json([
                    'error' => 'Token not found',
                    'type' => 'error',
                    'message' => 'A credencial de autorização não foi encontrada, realize login novamente.'
                ], 400);
            }
        }
        return $next($request);
    }
}
