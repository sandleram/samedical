<?php

namespace App\Interfaces\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class SaveParametroRequest extends FormRequest
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
            'tipo' => ['nullable', 'string', 'max:20'],
            'tipo_novo' => ['nullable', 'string', 'max:40'],
            'valor' => ['required', 'string', 'max:100'],
            'status' => ['required', 'integer', 'in:0,1'],
        ];
    }
}
