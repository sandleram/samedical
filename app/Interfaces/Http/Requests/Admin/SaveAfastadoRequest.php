<?php

namespace App\Interfaces\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class SaveAfastadoRequest extends FormRequest
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
            'situacao' => ['required', 'string', 'in:A,RT'],
            'data_inicio_afastamento' => ['nullable', 'date'],
            'data_fim_afastamento' => ['nullable', 'date'],
            'cid' => ['nullable', 'string', 'max:45'],
            'tipo_afastamento' => ['nullable', 'string', 'max:45'],
            'assistencia_medica' => ['nullable', 'string', 'max:45'],
            'plano_assistencia_medica' => ['nullable', 'string', 'max:45'],
            'acao_trabalhista' => ['nullable', 'integer', 'in:0,1'],
            'acao_inss' => ['nullable', 'integer', 'in:0,1,2'],
            'limbo_previdenciario' => ['nullable', 'integer', 'in:0,1'],
            'status' => ['nullable', 'integer', 'in:0,1'],
        ];
    }
}
