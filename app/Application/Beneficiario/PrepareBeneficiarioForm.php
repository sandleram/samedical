<?php

namespace App\Application\Beneficiario;

use App\Domain\Beneficiario\Beneficiario;
use App\Domain\Beneficiario\BeneficiarioRepositoryInterface;
use App\Domain\Empresa\EmpresaRepositoryInterface;
use App\Domain\Shared\TenantScope;

final class PrepareBeneficiarioForm
{
    public function __construct(
        private readonly BeneficiarioRepositoryInterface $beneficiarios,
        private readonly EmpresaRepositoryInterface $empresas,
    ) {}

    /**
     * @return array{beneficiario: ?Beneficiario, empresas: array<int, string>}
     */
    public function execute(int $clienteId, TenantScope $tenant, ?int $id = null): array
    {
        $beneficiario = null;
        if ($id !== null) {
            $beneficiario = $this->beneficiarios->findById($id, $tenant);
        }

        return [
            'beneficiario' => $beneficiario,
            'empresas' => $this->empresas->optionsForCliente($clienteId),
        ];
    }
}
