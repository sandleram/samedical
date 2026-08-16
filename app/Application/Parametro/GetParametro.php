<?php

namespace App\Application\Parametro;

use App\Domain\Parametro\Parametro;
use App\Domain\Parametro\ParametroRepositoryInterface;

final class GetParametro
{
    public function __construct(
        private readonly ParametroRepositoryInterface $repository,
    ) {}

    public function execute(int $id): ?Parametro
    {
        return $this->repository->findById($id);
    }
}
