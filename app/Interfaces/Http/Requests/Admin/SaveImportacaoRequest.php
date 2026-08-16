<?php

namespace App\Interfaces\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class SaveImportacaoRequest extends FormRequest
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
            'tipo_importacao' => ['required', 'string', 'in:beneficiario,afastado,beneficio_previdenciario,absenteismo,sinistro,fatura'],
            'arquivo' => ['required', 'file', 'max:51200'],
        ];
    }
}
