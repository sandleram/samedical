<?php

namespace App\Application\Atendimento;

use App\Domain\Atendimento\Atendimento;
use App\Domain\Atendimento\AtendimentoRepositoryInterface;
use App\Domain\Shared\TenantScope;

final class GetAtendimento
{
    public function __construct(
        private readonly AtendimentoRepositoryInterface $repository,
    ) {}

    public function execute(int $id, TenantScope $tenant): ?Atendimento
    {
        return $this->repository->findById($id, $tenant);
    }
}
