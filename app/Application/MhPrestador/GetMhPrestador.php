<?php

namespace App\Application\MhPrestador;

use App\Domain\MhPrestador\MhPrestador;
use App\Domain\MhPrestador\MhPrestadorRepositoryInterface;

final class GetMhPrestador
{
    public function __construct(
        private readonly MhPrestadorRepositoryInterface $repository,
    ) {}

    public function execute(int $id): ?MhPrestador
    {
        return $this->repository->findById($id);
    }
}
