<?php

namespace App\Interfaces\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class SavePlanoRequest extends FormRequest
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
            'codigo_operadora' => ['nullable', 'string', 'max:50'],
            'operadora_id' => ['nullable', 'integer', 'exists:operadora,id'],
            'tipo_beneficio_id' => ['nullable', 'integer', 'exists:tipo_beneficio,id'],
            'status' => ['required', 'integer', 'in:0,1'],
        ];
    }
}
