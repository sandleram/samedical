<?php

namespace App\Application\MhCritico;

use App\Domain\MhCritico\MhCritico;
use App\Domain\MhCritico\MhCriticoRepositoryInterface;

final class GetMhCritico
{
    public function __construct(
        private readonly MhCriticoRepositoryInterface $repository,
    ) {}

    public function execute(int $id): ?MhCritico
    {
        return $this->repository->findById($id);
    }
}
