<?php

namespace App\Domain\Importacao;

use App\Domain\Shared\PagedResult;
use App\Domain\Shared\TenantScope;

interface ImportacaoRepositoryInterface
{
    /**
     * @return PagedResult<Importacao>
     */
    public function search(ImportacaoSearchCriteria $criteria, TenantScope $tenant): PagedResult;

    public function findById(int $id, TenantScope $tenant): ?Importacao;

    /**
     * @param  array<string, mixed>  $data
     */
    public function create(array $data): Importacao;

    /**
     * Move upload to public/files/uploads/{subdir} and return stored filename.
     */
    public function storeUploadedFile(string $temporaryPath, string $originalFilename, string $extension, string $subdir): string;

    /**
     * @return array<string, string>
     */
    public function tipoImportacaoOptions(): array;
}
