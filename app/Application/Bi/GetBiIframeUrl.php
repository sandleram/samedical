<?php

namespace App\Application\Bi;

use App\Domain\Bi\BiRepositoryInterface;
use App\Domain\Shared\TenantScope;

final class GetBiIframeUrl
{
    public function __construct(
        private readonly BiRepositoryInterface $repository,
    ) {}

    public function execute(string $panel, TenantScope $tenant): string
    {
        return match ($panel) {
            'gerencial' => $this->repository->gerencialUrl($tenant),
            'medico' => $this->repository->medicoUrl($tenant),
            'rh' => $this->repository->rhUrl($tenant),
            default => '',
        };
    }
}
