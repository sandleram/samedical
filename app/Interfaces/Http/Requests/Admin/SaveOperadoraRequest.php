<?php

namespace App\Interfaces\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class SaveOperadoraRequest extends FormRequest
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
            'nome' => ['required', 'string', 'max:450'],
            'data_cancelamento' => ['nullable', 'date'],
            'status' => ['required', 'integer', 'in:0,1'],
        ];
    }
}
