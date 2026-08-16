<?php

namespace App\Interfaces\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class SavePerfilRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'id' => ['nullable', 'integer'],
            'nome' => ['required', 'string', 'max:100'],
            'tipo' => ['required', 'integer', 'in:0,1,2'],
            'status' => ['required', 'integer', 'in:0,1,2'],
            'PerfilModulo' => ['nullable', 'array'],
            'PerfilModulo.*.id' => ['nullable', 'integer'],
            'PerfilModulo.*.permissao' => ['nullable', 'integer', 'in:0,1,2,3'],
        ];
    }
}
