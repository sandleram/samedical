<?php

namespace App\Interfaces\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class SaveBeneficioPrevidenciarioRequest extends FormRequest
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
            'beneficiario_id' => ['required', 'integer', 'exists:beneficiario,id'],
            'empresa_id' => ['nullable', 'integer', 'exists:empresa,id'],
            'especie_bp_id' => ['required', 'integer', 'exists:especie_bp,id'],
            'nb' => ['nullable', 'integer'],
            'nit' => ['nullable', 'integer'],
            'num_requerimento' => ['nullable', 'integer'],
            'especie' => ['nullable', 'string', 'max:200'],
            'situacao' => ['nullable', 'string', 'max:45'],
            'data_inicio' => ['nullable', 'date'],
            'data_cessacao' => ['nullable', 'date'],
            'data_proxima_pericia' => ['nullable', 'date'],
            'data_entrada_requerimento' => ['nullable', 'date'],
            'conclusao_pericia_medica' => ['nullable', 'string'],
            'status' => ['nullable', 'integer', 'in:0,1'],
        ];
    }
}
