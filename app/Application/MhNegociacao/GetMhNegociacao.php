<?php

namespace App\Application\MhNegociacao;

use App\Domain\MhNegociacao\MhNegociacao;
use App\Domain\MhNegociacao\MhNegociacaoRepositoryInterface;

final class GetMhNegociacao
{
    public function __construct(
        private readonly MhNegociacaoRepositoryInterface $repository,
    ) {}

    public function execute(int $id): ?MhNegociacao
    {
        return $this->repository->findById($id);
    }
}
