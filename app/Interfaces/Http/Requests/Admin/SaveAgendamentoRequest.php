<?php

namespace App\Interfaces\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class SaveAgendamentoRequest extends FormRequest
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
            'atendimento_id' => ['required', 'integer', 'exists:atendimento,id'],
            'usuario_id' => ['required', 'integer', 'exists:usuario,id'],
            'usuario_agendamento_id' => ['required', 'integer', 'exists:usuario,id'],
            'data_hora' => ['nullable', 'date'],
            'descricao' => ['nullable', 'string'],
            'status' => ['required', 'integer', 'in:0,1'],
        ];
    }
}
