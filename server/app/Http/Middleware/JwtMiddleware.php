<?php

namespace App\Http\Middleware;

use Closure;
use Exception;
use App\User;
use Firebase\JWT\JWT;
use Firebase\JWT\ExpiredException;

class JwtMiddleware
{
    public function handle($request, Closure $next, $guard = null)
    {
        $token = $request->header('token');

        if(!$token) {
            return response()->json([
                'error' => 'Token doesn\'t exist.'
            ], 401);
        }

        try {
            $credentials = JWT::decode($token, env('JWT_SECRET'), ['HS256']);
        } catch(ExpiredException $e) {
            return response()->json([
                'error' => 'This token is expired.'
            ], 400);
        } catch(Exception $e) {
            return response()->json([
                'error' => 'This token has caused an error.'
            ], 400);
        }

        // $user = User::find($credentials->sub);

        $request->auth = $credentials;

        return $next($request);
    }
}
