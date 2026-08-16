<?php

namespace App\Application\Bi;

use App\Domain\Bi\BiRepositoryInterface;
use App\Domain\Shared\TenantScope;

final class ListBiDashboards
{
    public function __construct(
        private readonly BiRepositoryInterface $repository,
    ) {}

    /**
     * @return list<array{titulo: ?string, subtitulo: ?string, link: ?string}>
     */
    public function execute(int $usuarioId, TenantScope $tenant): array
    {
        return $this->repository->listDashboardsForUsuario($usuarioId, $tenant);
    }
}
