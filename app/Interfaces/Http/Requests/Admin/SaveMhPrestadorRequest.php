<?php

namespace App\Interfaces\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class SaveMhPrestadorRequest extends FormRequest
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
            'nome' => ['required', 'string', 'max:150'],
            'id_hubspot' => ['nullable', 'string', 'max:45'],
            'cidade' => ['nullable', 'string', 'max:60'],
            'estado' => ['nullable', 'string', 'max:2'],
            'praca' => ['nullable', 'string', 'max:100'],
            'atividade' => ['nullable', 'string', 'max:255'],
            'descricao' => ['nullable', 'string'],
            'status' => ['required', 'integer', 'in:0,1'],
        ];
    }
}
