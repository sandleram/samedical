<?php

namespace App\Application\GrupoEmpresarial;

use App\Domain\GrupoEmpresarial\GrupoEmpresarial;
use App\Domain\GrupoEmpresarial\GrupoEmpresarialRepositoryInterface;
use App\Domain\Shared\TenantScope;

final class SaveGrupoEmpresarial
{
    public function __construct(
        private readonly GrupoEmpresarialRepositoryInterface $repository,
    ) {}

    public function execute(SaveGrupoEmpresarialInput $input, TenantScope $tenant): GrupoEmpresarial
    {
        $attrs = $input->attributes;

        $cor = $attrs['cor'] ?? null;
        if (in_array($cor, ['#ffffff', '#000000', ''], true)) {
            $cor = null;
        }

        $payload = [
            'nome' => $attrs['nome'],
            'bi' => $attrs['bi'] ?? null,
            'cor' => $cor,
            'status' => (int) $attrs['status'],
        ];

        if (! empty($attrs['img_logo'])) {
            $payload['img_logo'] = $attrs['img_logo'];
        }

        if ($input->existingId !== null) {
            return $this->repository->update($input->existingId, $payload, $tenant);
        }

        $payload['data_cadastro'] = $input->now->format('Y-m-d H:i:s');
        $payload['status'] = $payload['status'] ?: 1;

        return $this->repository->create($payload);
    }
}
