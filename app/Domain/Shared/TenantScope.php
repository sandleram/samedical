<?php

namespace App\Domain\Shared;

/**
 * Escopo multi-tenant puro (sem sessão Laravel).
 * Preenchido na camada Interfaces a partir da sessão; aplicado na Infrastructure.
 */
final class TenantScope
{
    public function __construct(
        public readonly ?int $grupoEmpresarialId,
        public readonly ?int $clienteId,
    ) {}
}
