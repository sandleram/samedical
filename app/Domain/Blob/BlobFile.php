<?php

namespace App\Domain\Blob;

/**
 * Arquivo binário da tabela legado `blob`.
 */
final class BlobFile
{
    public function __construct(
        public readonly int $id,
        public readonly string $nome,
        public readonly ?string $tipo,
        public readonly ?int $tamanho,
        public readonly string $content,
        public readonly int $status,
    ) {}
}
