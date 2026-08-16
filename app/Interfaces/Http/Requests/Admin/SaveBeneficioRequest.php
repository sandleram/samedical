<?php

namespace App\Interfaces\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class SaveBeneficioRequest extends FormRequest
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
            'descricao' => ['required', 'string', 'max:100'],
            'breakeven' => ['nullable', 'integer'],
            'contrato' => ['nullable', 'string', 'max:50'],
            'operadora_id' => ['required', 'integer'],
            'tipo_beneficio_id' => ['required', 'integer', 'exists:tipo_beneficio,id'],
            'data_cancelamento' => ['nullable', 'date'],
            'status' => ['required', 'integer', 'in:0,1'],
        ];
    }
}
