<?php

namespace App\Application\Cliente;

use App\Domain\Cliente\Cliente;
use App\Domain\Cliente\ClienteRepositoryInterface;
use App\Domain\Shared\TenantScope;

final class SaveCliente
{
    public function __construct(
        private readonly ClienteRepositoryInterface $repository,
    ) {}

    public function execute(SaveClienteInput $input, TenantScope $tenant): Cliente
    {
        $attrs = $input->attributes;

        $payload = [
            'nome' => $attrs['nome'],
            'status' => (int) $attrs['status'],
        ];

        if (! empty($attrs['img_logo'])) {
            $payload['img_logo'] = $attrs['img_logo'];
        }

        if ($input->existingId !== null) {
            $payload['data_atualizacao'] = $input->now->format('Y-m-d H:i:s');

            return $this->repository->update($input->existingId, $payload, $tenant);
        }

        $payload['grupo_empresarial_id'] = $input->grupoEmpresarialId;
        $payload['usuario_criador_id'] = $input->userId;
        $payload['data_cadastro'] = $input->now->format('Y-m-d H:i:s');
        $payload['data_atualizacao'] = null;

        return $this->repository->create($payload);
    }
}
