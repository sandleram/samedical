<?php

namespace App\Interfaces\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class SaveBiRequest extends FormRequest
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
            'titulo' => ['required', 'string', 'max:20'],
            'subtitulo' => ['nullable', 'string', 'max:60'],
            'link' => ['required', 'string', 'max:255'],
            'observacao' => ['nullable', 'string'],
            'ordem' => ['nullable', 'integer'],
            'cliente_id' => ['nullable', 'integer'],
            'status' => ['nullable', 'integer'],
        ];
    }
}
