<?php

namespace App\Interfaces\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class SaveAbsenteismoRequest extends FormRequest
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
            'data_saida' => ['nullable', 'date'],
            'data_retorno' => ['nullable', 'date'],
            'cid' => ['nullable', 'string', 'max:45'],
            'hospital_clinica' => ['nullable', 'string', 'max:45'],
            'profissional' => ['nullable', 'string', 'max:45'],
            'num_crm' => ['nullable', 'string', 'max:45'],
            'qtde_dias_atestado' => ['nullable', 'integer'],
            'observacao' => ['nullable', 'string'],
            'situacao' => ['nullable', 'string', 'max:150'],
            'status' => ['nullable', 'integer', 'in:0,1'],
        ];
    }
}
