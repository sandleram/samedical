<?php

namespace App\Application\Cliente;

use App\Domain\Cliente\Cliente;
use App\Domain\Cliente\ClienteRepositoryInterface;

final class GetClienteForSelecao
{
    public function __construct(
        private readonly ClienteRepositoryInterface $repository,
    ) {}

    public function execute(int $id): ?Cliente
    {
        return $this->repository->findForSelecao($id);
    }
}
