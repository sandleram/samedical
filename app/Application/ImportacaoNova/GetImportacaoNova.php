<?php

namespace App\Application\ImportacaoNova;

use App\Domain\ImportacaoNova\ImportacaoNova;
use App\Domain\ImportacaoNova\ImportacaoNovaRepositoryInterface;
use App\Domain\Shared\TenantScope;

final class GetImportacaoNova
{
    public function __construct(
        private readonly ImportacaoNovaRepositoryInterface $repository,
    ) {}

    public function execute(int $id, TenantScope $tenant): ?ImportacaoNova
    {
        return $this->repository->findById($id, $tenant);
    }
}
