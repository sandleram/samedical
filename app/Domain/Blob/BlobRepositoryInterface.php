<?php

namespace App\Domain\Blob;

interface BlobRepositoryInterface
{
    /**
     * Lookup legado: md5(id) + status ativo.
     */
    public function findActiveByIdMd5(string $idMd5): ?BlobFile;
}
