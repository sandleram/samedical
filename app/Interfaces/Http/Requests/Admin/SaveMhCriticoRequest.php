<?php

namespace App\Interfaces\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class SaveMhCriticoRequest extends FormRequest
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
            'principal' => ['required', 'integer', 'in:0,1'],
            'mh_prestador_principal_id' => ['required', 'integer', 'exists:mh_prestador,id'],
            'mh_prestador_id' => ['nullable', 'integer', 'exists:mh_prestador,id'],
            'opcao' => ['nullable', 'integer'],
            'status' => ['required', 'integer', 'in:0,1'],
        ];
    }
}
