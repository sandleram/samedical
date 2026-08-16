<?php

namespace App\Domain\ImportacaoNova;

use App\Domain\Shared\PagedResult;
use App\Domain\Shared\TenantScope;

interface ImportacaoNovaRepositoryInterface
{
    /**
     * @return PagedResult<ImportacaoNova>
     */
    public function search(ImportacaoNovaSearchCriteria $criteria, TenantScope $tenant): PagedResult;

    public function findById(int $id, TenantScope $tenant): ?ImportacaoNova;

    /**
     * @param  array<string, mixed>  $data
     */
    public function create(array $data): ImportacaoNova;

    /**
     * @param  array<string, mixed>  $data
     */
    public function update(int $id, array $data, TenantScope $tenant): ImportacaoNova;

    public function storeUploadedFile(string $temporaryPath, string $originalFilename, string $extension, string $subdir): string;

    /**
     * @return array<string, string>
     */
    public function tipoImportacaoOptions(): array;

    /**
     * @return array<int, string>
     */
    public function statusProcessoOptions(): array;
}
