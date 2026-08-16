<?php

namespace App\Interfaces\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class SaveMhNegociacaoRequest extends FormRequest
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
            'mh_prestador_id' => ['required', 'integer', 'exists:mh_prestador,id'],
            'tipo_negocio' => ['required', 'integer'],
            'usuario_negociador_id' => ['nullable', 'integer'],
            'status' => ['required', 'integer', 'in:0,1'],
        ];
    }
}
