<?php

namespace App\Interfaces\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class SaveEmpresaRequest extends FormRequest
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
            'nome' => ['required', 'string', 'max:45'],
            'razao_social' => ['nullable', 'string', 'max:100'],
            'nome_fantasia' => ['nullable', 'string', 'max:100'],
            'cnpj' => ['required', 'string', 'max:19'],
            'inscricao_estadual' => ['nullable', 'string', 'max:25'],
            'inscricao_municipal' => ['nullable', 'string', 'max:25'],
            'numero_funcionarios' => ['nullable', 'integer'],
            'descricao' => ['nullable', 'string', 'max:1024'],
            'porte' => ['nullable', 'string', 'max:10'],
            'faturamento' => ['nullable', 'string', 'max:50'],
            'tipo' => ['nullable', 'string', 'max:15'],
            'endereco' => ['nullable', 'string', 'max:100'],
            'numero' => ['nullable', 'string', 'max:10'],
            'complemento' => ['nullable', 'string', 'max:100'],
            'bairro' => ['nullable', 'string', 'max:50'],
            'cidade' => ['nullable', 'string', 'max:50'],
            'estado' => ['nullable', 'string', 'max:2'],
            'cep' => ['nullable', 'string', 'max:9'],
            'telefone' => ['nullable', 'string', 'max:50'],
            'email' => ['nullable', 'email', 'max:100'],
            'site' => ['nullable', 'string', 'max:250'],
            'status' => ['required', 'integer', 'in:0,1,2'],
        ];
    }
}
