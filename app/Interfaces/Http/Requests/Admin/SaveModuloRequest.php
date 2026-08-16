<?php

namespace App\Interfaces\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class SaveModuloRequest extends FormRequest
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
            'modulo_id' => ['required', 'integer'],
            'nome' => ['required', 'string', 'max:45'],
            'controller' => ['required', 'string', 'max:45'],
            'order' => ['nullable', 'integer'],
            'menu' => ['nullable', 'integer'],
            'icon' => ['nullable', 'string', 'max:35'],
            'status' => ['required', 'integer', 'in:0,1,2'],
        ];
    }
}
