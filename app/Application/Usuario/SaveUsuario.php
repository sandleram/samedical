<?php

namespace App\Application\Usuario;

use App\Domain\Shared\TenantScope;
use App\Domain\Usuario\Usuario;
use App\Domain\Usuario\UsuarioRepositoryInterface;
use RuntimeException;

final class SaveUsuario
{
    public function __construct(
        private readonly UsuarioRepositoryInterface $usuarios,
    ) {}

    public function execute(SaveUsuarioInput $input, TenantScope $tenant): Usuario
    {
        $attrs = $input->attributes;
        $perfilId = (int) ($attrs['perfil_id'] ?? 0);

        if (! $input->isRoot && $perfilId === 1) {
            throw new RuntimeException('Erro: Perfil selecionado não existe!');
        }

        $senha = isset($attrs['senha']) ? (string) $attrs['senha'] : '';
        if ($senha !== '' && preg_match('/ /', $senha)) {
            throw new RuntimeException('A SENHA não pode conter espaços, favor corrigir!!');
        }

        $payload = [
            'perfil_id' => $perfilId,
            'apelido' => $attrs['apelido'],
            'nome' => $attrs['nome'],
            'usuario' => $attrs['usuario'],
            'email' => $attrs['email'],
            'email_gestao' => $attrs['email_gestao'] ?? null,
            'sexo' => $attrs['sexo'] ?? null,
            'rg' => $attrs['rg'] ?? null,
            'cpf' => isset($attrs['cpf']) ? str_replace(['.', '-'], '', (string) $attrs['cpf']) : null,
            'data_nascimento' => $attrs['data_nascimento'] ?? null,
            'tel1_tipo' => $attrs['tel1_tipo'] ?? null,
            'tel1' => $attrs['tel1'] ?? null,
            'tel2_tipo' => $attrs['tel2_tipo'] ?? null,
            'tel2' => $attrs['tel2'] ?? null,
            'tel3_tipo' => $attrs['tel3_tipo'] ?? null,
            'tel3' => $attrs['tel3'] ?? null,
            'observacao' => $attrs['observacao'] ?? null,
            'status' => isset($attrs['status']) ? (int) $attrs['status'] : 1,
        ];

        if ($senha !== '') {
            $payload['senha'] = $senha;
        }

        if ($input->existingId === null) {
            $payload['usuario_criador_id'] = $input->userId;
            $payload['grupo_empresarial_id'] = $input->grupoEmpresarialId;
            $payload['data_cadastro'] = $input->now->format('Y-m-d H:i:s');
            if ($senha === '') {
                throw new RuntimeException('Senha obrigatória para novo usuário.');
            }
        } elseif ($senha !== '') {
            // Legado: data_atualizacao só quando a senha é alterada.
            $payload['data_atualizacao'] = $input->now->format('Y-m-d H:i:s');
        }

        $clienteIds = array_map('intval', (array) ($attrs['cliente_id'] ?? []));
        $biIds = array_map('intval', (array) ($attrs['bi_id'] ?? []));

        return $this->usuarios->save(
            $payload,
            $clienteIds,
            $biIds,
            $input->existingId,
            $tenant,
            $input->isRoot,
        );
    }
}
