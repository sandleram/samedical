<?php

namespace App\Interfaces\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class SaveProcedimentoRequest extends FormRequest
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
            'cod_procedimento' => ['nullable', 'string', 'max:50'],
            'ds_procedimento' => ['required', 'string', 'max:300'],
            'tipo_procedimento' => ['nullable', 'string', 'max:200'],
            'Grupo' => ['nullable', 'string', 'max:100'],
            'Subgrupo' => ['nullable', 'string', 'max:250'],
            'status' => ['required', 'integer', 'in:0,1'],
        ];
    }
}
