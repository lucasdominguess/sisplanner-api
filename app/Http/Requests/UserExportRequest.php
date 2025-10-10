<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class UserExportRequest extends FormRequest
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
            'ext' => ['required', 'string', Rule::in(['xlsx', 'pdf', 'csv'])],
            'status_id' => ['nullable', 'integer', 'exists:status,id'],
            'status' => ['nullable', 'string', 'exists:status,name'],
            'role_id' => ['nullable', 'integer', 'exists:roles,id'],
        ];

    }
    public function messages()
    {
        return [
            'ext.required' => 'O campo Extensão para donwload do arquivo é obrigatório.',
            'ext.string' => 'Extensão para donwload do arquivo deve ser uma string xlsx ,pdf ou csv.',
            'ext.in' => 'Extensão para donwload do arquivo deve ser uma string xlsx ,pdf ou csv.',
            'status_id.exists' => 'O status informado não existe.',
            'status.exists' => 'O status informado não existe.',
            'role_id.exists' => 'O role informado não existe.',
        ];
    }
}
