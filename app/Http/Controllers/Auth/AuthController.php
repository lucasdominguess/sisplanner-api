<?php

namespace App\Http\Controllers\Auth;

use Exception;
use App\Http\Controllers\Controller;
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
use App\Mail\WelcomeUserMail;
use Illuminate\Support\Facades\Mail;

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
         Mail::to($user->email)->send(new WelcomeUserMail($user));
        return response()->json(['message' => 'Usuário criado com sucesso! Aguarde ativação pelo Administrador', 'user' => $user], 201);
    }

    public function login(UserLoginRequest $request)
    {
        $credentials = $request->validated();
        $credentials['status_id'] = Status::ACTIVE->value;

        try {
            if (!$token = JWTAuth::attempt($credentials)) {

            $user = User::where('email', $credentials['email'])->first();

            if ($user && is_null($user->password) && !is_null($user->google_id)) {
                return response()->json([
                    'message' => 'Você se registrou com o Google. Deseja realizar login com o Google?',
                    'action_required' => 'CREATE_PASSWORD_FOR_SOCIAL_ACCOUNT' // Código para o front-end
                ], 422);
}

                Log::warning('Tentativa de login falhou para o email: ' . $credentials['email']);


                return response()->json(['message' => 'Credenciais inválidas ou usuário inativo','googleaccount'=>'realize login com google'], 401);
            }
        } catch (\Tymon\JWTAuth\Exceptions\JWTException $e) {
            Log::error('Não foi possível criar o token: ' . $e->getMessage());
            return response()->json(['message' => 'Não foi possível criar o token'], 500);
        } catch (Exception $e) {
            Log::error('erro em login: ' . $e->getMessage());
            return response()->json(['message' => $e->getMessage()], 500);
        }
        Log::info('Usuário logado com sucesso: ' . $credentials['email']);

        // Log::channel('telegram')->info('Usuário logado com sucesso: ' . $credentials['email']);s
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
