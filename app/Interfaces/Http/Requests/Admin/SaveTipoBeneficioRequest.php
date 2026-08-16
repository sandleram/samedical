<?php

namespace App\Interfaces\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class SaveTipoBeneficioRequest extends FormRequest
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
            'descricao' => ['required', 'string', 'max:45'],
            'data_cancelamento' => ['nullable', 'date'],
            'status' => ['required', 'integer', 'in:0,1'],
        ];
    }
}
