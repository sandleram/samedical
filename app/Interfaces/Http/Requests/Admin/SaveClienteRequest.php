<?php

namespace App\Interfaces\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class SaveClienteRequest extends FormRequest
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
            'nome' => ['required', 'string', 'max:450'],
            'status' => ['required', 'integer', 'in:0,1,2'],
            'img_logo' => ['nullable', 'string', 'max:255'],
        ];
    }
}
