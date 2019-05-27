<?php
/**
 * Created by PhpStorm.
 * User: Iago Maia
 * Date: 07/01/2019
 * Time: 09:31
 */

namespace App\Http\Middleware;
use Closure;
use Exception;
use App\User;
use Firebase\JWT\JWT;
use Firebase\JWT\ExpiredException;
use Illuminate\Support\Facades\Auth;

class JwtMiddleware
{
    public function handle($request, Closure $next, $guard = null)
    {
        $token = $request->bearerToken();

        if(!$token) {
            // Unauthorized response if token not there
            return response()->json([
                'error' => 'Token não informado.',
                'redirect_login' => true
            ], 401);
        }
        try {
            $credentials = JWT::decode($token, env('JWT_SECRET'), ['HS256']);
        } catch(ExpiredException $e) {
            return response()->json([
                'error' => 'Token expirou.',
                'redirect_login' => true
            ], 400);
        } catch(Exception $e) {
            return response()->json([
                'error' => 'Ocorreu um erro ao verificar a integridade do token.',
                'redirect_login' => true
            ], 400);
        }
        $user = User::find($credentials->sub);
        if($user) {
            // Now let's put the user in the request class so that you can grab it from there
            $request->auth = $user;
            Auth::setUser($user);
            return $next($request);
        }
        return response()->json([
            'error' => 'Usuário autenticado não encontrado.',
            'redirect_login' => true
        ], 400);
    }
}