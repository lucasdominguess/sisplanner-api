<?php

namespace App\Http\Controllers;

use Exception;
use App\Enums\Roles;
use App\Enums\Status;
use App\Models\Users\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\UserLoginRequest;
use App\Http\Requests\UserRegisterRequest;

class AuthController extends Controller
{
    public function register(UserRegisterRequest $request)
    {
        $validated = $request->validated();

        $user = DB::transaction(function () use ($validated) {
            $validated['status_id'] = Status::INACTIVE->value;
            $user = User::create($validated);

            // sync() ou attach() funcionam aqui. attach() é um pouco mais semântico para um novo registro.
            // $user->roles()->sync([Roles::USER->value]);
            $user->roles()->attach(Roles::USER->value);

        return $user;
    });

        Log::info('Usuário criado com sucesso: ' . $user->name);
        return response()->json(['message' => 'Usuário criado com sucesso! Aguarde ativação pelo Administrador', 'user' => $user], 201);
    }

    public function login(UserLoginRequest $request)
    {
        $credentials = $request->validated();
        $credentials['status_id'] = Status::ACTIVE->value;

        try {
            if (!$token = JWTAuth::attempt($credentials)) {
                Log::warning('Tentativa de login falhou para o email: ' . $credentials['email']);
                return response()->json(['message' => 'Credenciais inválidas ou usuário inativo'], 401);
            }
        } catch (\Tymon\JWTAuth\Exceptions\JWTException $e) {
            Log::error('Não foi possível criar o token: ' . $e->getMessage());
            return response()->json(['message' => 'Não foi possível criar o token'], 500);
        } catch (Exception $e) {
            Log::error('erro em login: ' . $e->getMessage());
            return response()->json(['message' => $e->getMessage()], 500);
        }
        Log::info('Usuário logado com sucesso: ' . $credentials['email']);

        return response()->json([
            'user' => $credentials['email'],
        ], 200, ['access_token' => $token, 'token_type' => 'bearer']);
    }
    public function logout()
    {
        auth('api')->logout();
        return response()->json(['message' => 'Logout realizado com sucesso']);
    }


    public function refresh()
    {
        $newToken = auth('api')->refresh();
        return $this->respondWithToken($newToken);
    }
    protected function respondWithToken(string $token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60,
            'user' => auth('api')->user()
        ]);
    }
}
