<?php

namespace App\Domain\Plano;

use App\Domain\Shared\PagedResult;

interface PlanoRepositoryInterface
{
    /**
     * @return PagedResult<Plano>
     */
    public function search(PlanoSearchCriteria $criteria): PagedResult;

    public function findById(int $id): ?Plano;

    /**
     * @param  array<string, mixed>  $data
     */
    public function create(array $data): Plano;

    /**
     * @param  array<string, mixed>  $data
     */
    public function update(int $id, array $data): Plano;

    public function nextOrdem(?int $clienteId, ?int $operadoraId, ?int $tipoBeneficioId): int;

    /**
     * @return array<int|string, string>
     */
    public function tipoBeneficioOptions(bool $withPlaceholder = true): array;
}
