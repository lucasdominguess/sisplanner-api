<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Log;

class RefreshTokenMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        try {
            
            if(app()->router->is('list_unidades')) return $response;
            
            // faz o refresh SEM invalidar o token anterior
            $newToken = JWTAuth::refresh(JWTAuth::getToken(), /* resetBlacklist */ false);
            $response->headers->set('Authorization', $newToken);

            Log::info('Token renovado com sucesso');
        } catch (\Exception $e) {
            Log::warning('Não foi possível renovar o token: ' . $e->getMessage());
        }
        return $response;
    }
}
