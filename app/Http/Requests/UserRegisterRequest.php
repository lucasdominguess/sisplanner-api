<?php

namespace App\Http\Requests;

use Illuminate\Support\Facades\Log;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class UserRegisterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name'=>['required','min:3','max:70'],
            'email'=>['required','email','unique:users'],
            'password'=>['required','min:8','max:70'],
        ];
    }
    public function messages(){
        return [
            'name.required' => 'O campo nome é obrigatório',
            'name.min' => 'O campo nome deve conter no mínimo 3 caracteres',
            'name.max' => 'O campo nome deve conter no máximo 70 caracteres',
            'email.required' => 'O campo email é obrigatório',
            'email.email' => 'O campo email deve ser um endereço de email válido',
            'email.unique' => 'O email já está em uso',
            'password.required' => 'O campo senha é obrigatório',
            'password.min' => 'O campo senha deve conter no mínimo 8 caracteres',
            'password.max' => 'O campo senha deve conter no máximo 70 caracteres',
        ];
    }
     protected function failedValidation($validator): void
    {
    $errors = $validator->errors()->toArray();

    Log::error('Erro de validação em UserRequest', $errors);

    throw new HttpResponseException(
        response()->json([
            'message' => 'Os dados fornecidos são inválidos.',
            'errors'  => $errors,
        ], 422)
    );
    }
}
