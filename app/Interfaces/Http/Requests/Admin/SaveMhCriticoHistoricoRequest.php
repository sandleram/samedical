<?php

namespace App\Interfaces\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class SaveMhCriticoHistoricoRequest extends FormRequest
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
            'ciclo' => ['required', 'integer'],
            'status_ciclo' => ['required', 'integer'],
            'descricao' => ['required', 'string'],
            'status' => ['required', 'integer', 'in:0,1'],
        ];
    }
}
