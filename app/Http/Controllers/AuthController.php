<?php

namespace App\Http\Controllers;

use App\Enums\Status;
use App\Models\Users\User;
use Exception;
use Illuminate\Http\Request;
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
        $user = User::create($validated);
        $user->roles()->sync([2]);
        return response()->json(['message' => 'Usuário criado com sucesso! Aguarde ativação pelo Administrador' , 'user' => $user],201);
    }

    public function login(UserLoginRequest $request)
    {
        $request = $request->validated();

        $credentials['status_id'] = Status::INACTIVE->value;
        try {
            if (!$token = JWTAuth::claims($credentials)->attempt($request)) {

                Log::warning('Tentativa de login falhou para o email: ' . $request['email']);
                return response()->json(['message' => 'Credenciais inválidas'], 401);
            }
        } catch (\Tymon\JWTAuth\Exceptions\JWTException $e) {
            Log::error('Não foi possível criar o token: ' . $e->getMessage());
            return response()->json(['message' => 'Não foi possível criar o token'], 500);
        }

        catch(Exception $e){
            Log::error('erro em login: ' . $e->getMessage());
            return response()->json(['message' => $e->getMessage()], 500);
        }
        Log::info('Usuário logado com sucesso: ' . $request['email']);

        return response()->json([
            'user' =>$request['email'],
        ], 200, ['Authorization' => $token]);
    }
}
