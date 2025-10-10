<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Foundation\Http\FormRequest;

class UserUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Pega o usuário que está sendo editado a partir da rota (ex: /api/users/5)
        $userToUpdate = $this->route('user');

        // Verifica se o usuário autenticado tem permissão de 'is-admin' (Gate)
        // OU se o ID do usuário autenticado é o mesmo do usuário que está sendo editado.
        return $this->user()->can('Gate-admin') || $this->user()->id === $userToUpdate->id;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {

        // Pega o usuário que está sendo editado a partir do parâmetro da rota
        $userToUpdateId = $this->route('user')->id;

        $rules = [
            // 'sometimes' significa: valide apenas se o campo estiver presente na requisição.
            'name' => ['sometimes', 'required', 'string', 'min:3', 'max:70'],
            'email' => [
                'sometimes',
                'required',
                'email',
                // A regra 'unique' precisa ignorar o ID do próprio usuário que estamos editando.
                Rule::unique('users')->ignore($userToUpdateId),
            ],

            // --- Lógica da Senha ---
            // 'current_password' é obrigatório apenas se o campo 'password' for enviado.
            'current_password' => [
                'required_with:password',
                // Regra customizada para verificar se a senha antiga está correta
                function ($attribute, $value, $fail) {
                    if (!Hash::check($value, $this->user()->password)) {
                        $fail('A senha atual informada está incorreta.');
                    }
                },
            ],
            // 'password' é opcional, mas se enviado, deve seguir as regras e ser confirmado
            'password' => [
                'sometimes',
                'required',
                'confirmed', // Exige que um campo 'password_confirmation' seja enviado junto
                Password::min(8)->letters()->mixedCase()->numbers()->symbols(),
            ],
        ];

        // --- Lógica de Admin ---
        // Adiciona regras para role e status APENAS se o usuário logado for admin
        if ($this->user()->can('is-admin')) {
            $rules['role_id'] = ['sometimes', 'required', 'integer', 'exists:roles,id'];
            $rules['status_id'] = ['sometimes', 'required', 'integer', 'exists:status,id'];
        }

        return $rules;

    }
    public function messages()
    {
        return [
            'current_password.required_with' => 'A senha atual é obrigatória.',
            'password.required_with' => 'A nova senha é obrigatória.',
            'password.confirmed' => 'A confirmação da nova senha é obrigatória.',
            'password.min' => 'A nova senha precisa ter no mínimo 8 caracteres.',
            'password.letters' => 'A nova senha precisa conter pelo menos uma letra.',
            'password.mixed' => 'A nova senha precisa conter pelo menos uma letra maiúscula e uma letra minúscula.',
            'password.numbers' => 'A nova senha precisa conter pelo menos um número.',
            'password.symbols' => 'A nova senha precisa conter pelo menos um símbolo.',
            'role_id.required' => 'O campo role_id é obrigatório.',
            'role_id.integer' => 'O campo role_id precisa ser um inteiro.',
            'role_id.exists' => 'O campo role_id precisa ser um role existente.',
            'status_id.required' => 'O campo status_id é obrigatório.',
            'status_id.integer' => 'O campo status_id precisa ser um inteiro.',
            'status_id.exists' => 'O campo status_id precisa ser um status existente.',
            'name.required' => 'O campo name é obrigatório.',
            'name.string' => 'O campo name precisa ser uma string.',
            'name.min' => 'O campo name precisa ter no mínimo 3 caracteres.',
            'name.max' => 'O campo name precisa ter no máximo 70 caracteres.',
            'email.required' => 'O campo email é obrigatório.',
            'email.email' => 'O campo email precisa ser um endereço de email válido.',
            'email.unique' => 'O email já esta em uso.',

        ];
    }
}
