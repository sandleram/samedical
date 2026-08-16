<?php

namespace App\Application\Blob;

use App\Domain\Blob\BlobFile;
use App\Domain\Blob\BlobRepositoryInterface;

final class DownloadBlob
{
    public function __construct(
        private readonly BlobRepositoryInterface $repository,
    ) {}

    public function execute(string $idMd5): ?BlobFile
    {
        if (strlen($idMd5) !== 32 || ! ctype_xdigit($idMd5)) {
            return null;
        }

        return $this->repository->findActiveByIdMd5(strtolower($idMd5));
    }
}
