<?php

namespace App\Interfaces\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class SaveAtendimentoRequest extends FormRequest
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
            'tipo_atendimento' => ['required', 'integer'],
            'cid' => ['nullable', 'string', 'max:6'],
            'descricao' => ['nullable', 'string'],
            'forma_atendimento' => ['nullable', 'integer'],
            'status_atendimento' => ['required', 'integer'],
            'data_conclusao' => ['nullable', 'date'],
            'status' => ['nullable', 'integer', 'in:0,1'],
        ];
    }
}
