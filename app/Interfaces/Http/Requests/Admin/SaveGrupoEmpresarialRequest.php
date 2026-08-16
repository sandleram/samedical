<?php

namespace App\Interfaces\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class SaveGrupoEmpresarialRequest extends FormRequest
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
            'nome' => ['required', 'string', 'max:60'],
            'bi' => ['nullable', 'string', 'max:255'],
            'cor' => ['nullable', 'string', 'max:10'],
            'status' => ['required', 'integer', 'in:0,1,2'],
            'img_logo' => ['nullable', 'string', 'max:255'],
        ];
    }
}
