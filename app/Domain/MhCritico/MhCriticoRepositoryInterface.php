<?php

namespace App\Domain\MhCritico;

use App\Domain\Shared\PagedResult;

interface MhCriticoRepositoryInterface
{
    /**
     * @return PagedResult<MhCritico>
     */
    public function searchPrincipals(MhCriticoSearchCriteria $criteria): PagedResult;

    /**
     * Opções (principal=0) indexadas por mh_prestador_principal_id.
     *
     * @return array<int, list<MhCritico>>
     */
    public function listSubsidiaries(MhCriticoSearchCriteria $criteria): array;

    public function findById(int $id): ?MhCritico;

    public function exists(int $id): bool;

    /**
     * @param  array<string, mixed>  $data
     */
    public function create(array $data): MhCritico;

    /**
     * @param  array<string, mixed>  $data
     */
    public function update(int $id, array $data): MhCritico;

    /**
     * @return array{listPrestadorAll: array<int|string, string>, listPrestadorSemUsados: array<int|string, string>}
     */
    public function formOptions(): array;
}
