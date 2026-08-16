<?php

namespace App\Interfaces\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SaveUsuarioRequest extends FormRequest
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
        $existingId = $this->route('id') ?? $this->input('id');
        $existingId = is_numeric($existingId) ? (int) $existingId : null;

        $usuarioRule = Rule::unique('usuario', 'usuario');
        $emailRule = Rule::unique('usuario', 'email');
        if ($existingId) {
            $usuarioRule->ignore($existingId);
            $emailRule->ignore($existingId);
        }

        $senhaRules = $existingId
            ? ['nullable', 'string', 'max:120']
            : ['required', 'string', 'max:120'];

        return [
            'id' => ['nullable', 'integer'],
            'perfil_id' => ['required', 'integer', 'exists:perfil,id'],
            'apelido' => ['required', 'string', 'max:10'],
            'nome' => ['required', 'string', 'max:65'],
            'usuario' => ['required', 'string', 'max:60', 'regex:/^\S+$/', $usuarioRule],
            'email' => ['required', 'email', 'max:120', $emailRule],
            'email_gestao' => ['nullable', 'email', 'max:120'],
            'senha' => $senhaRules,
            'retry_senha' => ['nullable', 'string', 'same:senha'],
            'sexo' => ['nullable', 'string', 'max:15'],
            'rg' => ['nullable', 'string', 'max:20'],
            'cpf' => ['nullable', 'string', 'max:20'],
            'data_nascimento' => ['nullable', 'date'],
            'tel1_tipo' => ['nullable', 'string', 'max:20'],
            'tel1' => ['nullable', 'string', 'max:15'],
            'tel2_tipo' => ['nullable', 'string', 'max:20'],
            'tel2' => ['nullable', 'string', 'max:15'],
            'tel3_tipo' => ['nullable', 'string', 'max:20'],
            'tel3' => ['nullable', 'string', 'max:15'],
            'observacao' => ['nullable', 'string'],
            'status' => ['nullable', 'integer', 'in:0,1,2'],
            'cliente_id' => ['nullable', 'array'],
            'cliente_id.*' => ['integer', 'exists:cliente,id'],
            'bi_id' => ['nullable', 'array'],
            'bi_id.*' => ['integer', 'exists:bi,id'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'usuario.regex' => 'Nome de USUÁRIO não pode conter espaços, favor corrigir!!',
            'retry_senha.same' => 'Confirmação diferente da senha. Por favor, faça a correção e tente novamente.',
        ];
    }
}
