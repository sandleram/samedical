<?php

namespace App\Interfaces\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SaveBeneficiarioRequest extends FormRequest
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
        $clienteId = (int) session('cliente_id');

        return [
            'id' => ['nullable', 'integer'],
            'nome' => ['required', 'string', 'max:150'],
            'nome_social' => ['nullable', 'string', 'max:60'],
            'email' => ['required', 'email', 'max:60'],
            'empresa_id' => [
                'required',
                'integer',
                Rule::exists('empresa', 'id')->where(fn ($q) => $q->where('cliente_id', $clienteId)),
            ],
            'situacao' => ['nullable', 'string', 'max:150'],
            'beneficio' => ['nullable', 'string', 'max:150'],
            'valor_do_seguro' => ['nullable', 'string', 'max:20'],
            'cpf' => ['required', 'string', 'max:20'],
            'rg' => ['nullable', 'string', 'max:20'],
            'sexo' => ['required', 'string', 'max:15'],
            'estado_civil' => ['nullable', 'string', 'max:20'],
            'data_nascimento' => ['required', 'date'],
            'altura' => ['nullable', 'string', 'max:10'],
            'peso' => ['nullable', 'string', 'max:10'],
            'endereco' => ['nullable', 'string', 'max:200'],
            'bairro' => ['nullable', 'string', 'max:150'],
            'cidade' => ['nullable', 'string', 'max:60'],
            'estado' => ['nullable', 'string', 'max:2'],
            'cep' => ['nullable', 'string', 'max:9'],
            'agencia' => ['nullable', 'string', 'max:10'],
            'conta' => ['nullable', 'string', 'max:20'],
            'tipo_de_conta' => ['nullable', 'string', 'max:20'],
            'profissao' => ['nullable', 'string', 'max:50'],
            'ocupacao' => ['nullable', 'string', 'max:50'],
            'pessoa_politicamente_exposta' => ['nullable', 'string', 'max:60'],
            'realiza_alguma_atividade_perigosa_na_profissao' => ['nullable', 'string', 'max:60'],
            'possui_deficiencia' => ['nullable', 'string', 'max:60'],
            'telefone_tipo' => ['required', 'string', 'max:10'],
            'telefone' => ['required', 'string', 'max:15'],
            'telefone1_tipo' => ['nullable', 'string', 'max:10'],
            'telefone1' => ['nullable', 'string', 'max:15'],
            'observacao' => ['nullable', 'string'],
            'cod_matricula' => ['nullable', 'string', 'max:100'],
            'pis' => ['nullable', 'string', 'max:45'],
            'status' => ['nullable', 'integer', 'in:0,1'],
        ];
    }
}
