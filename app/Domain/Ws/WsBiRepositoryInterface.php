<?php

namespace App\Domain\Ws;

interface WsBiRepositoryInterface
{
    /**
     * @return list<array<string, mixed>>
     */
    public function listBeneficiarios(?int $clienteId, int $limit): array;
}
