<?php

namespace App\Http\Controllers\Auth;

use App\Enums\Status;

use App\Models\Users\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Interfaces\SocialAuthInterface;
use Laravel\Socialite\Facades\Socialite;

class SocialiteController extends Controller
{
    public function handleGoogleCallback(Request $request, SocialAuthInterface $socialAuth)
    {
        $request->validate(['token' => 'required|string',]);

        try {
            // Usa o token para obter os dados do usuário do Google
            $user = $socialAuth->authenticate($request->token);

            if (!$token = auth('api')->login($user)) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }
            return $this->respondWithToken($token);
            
        } catch (\Exception $e) {
            Log::error('Falha na autenticação social: '.$e->getMessage());
            return response()->json(['error' => 'Invalid credentials provided.'], 401);
        }
    }

    /**
     * Estrutura de resposta padrão para o token JWT.
     */
    protected function respondWithToken(string $token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60, // tempo de expiração em segundos
            'user' => auth('api')->user()
        ]);
    }
}
