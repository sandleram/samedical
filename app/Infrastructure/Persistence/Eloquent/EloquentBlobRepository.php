<?php

namespace App\Infrastructure\Persistence\Eloquent;

use App\Domain\Blob\BlobFile;
use App\Domain\Blob\BlobRepositoryInterface;
use App\Models\Blob as BlobModel;

final class EloquentBlobRepository implements BlobRepositoryInterface
{
    public function findActiveByIdMd5(string $idMd5): ?BlobFile
    {
        $row = BlobModel::query()
            ->whereRaw('md5(id) = ?', [strtolower($idMd5)])
            ->where('status', 1)
            ->first();

        if (! $row) {
            return null;
        }

        return new BlobFile(
            id: (int) $row->id,
            nome: (string) ($row->nome ?? ''),
            tipo: $row->tipo !== null ? (string) $row->tipo : null,
            tamanho: $row->tamanho !== null ? (int) $row->tamanho : null,
            content: $row->binaryContent() ?? '',
            status: (int) $row->status,
        );
    }
}
