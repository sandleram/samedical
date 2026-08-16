<?php

namespace App\Interfaces\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class SaveSubfaturaRequest extends FormRequest
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
            'beneficio_id' => ['required', 'integer'],
            'descricao' => ['required', 'string', 'max:450'],
            'codigo' => ['required', 'string', 'max:45'],
            'data_cancelamento' => ['nullable', 'date'],
            'status' => ['required', 'integer', 'in:0,1'],
        ];
    }
}
