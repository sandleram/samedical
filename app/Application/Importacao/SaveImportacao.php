<?php

namespace App\Application\Importacao;

use App\Domain\Importacao\Importacao;
use App\Domain\Importacao\ImportacaoRepositoryInterface;
use DateTimeImmutable;

final class SaveImportacao
{
    public function __construct(
        private readonly ImportacaoRepositoryInterface $repository,
    ) {}

    /**
     * Registra upload + registro. Processamento carga_* permanece deferido.
     *
     * @param  array{tipo_importacao: string}  $attributes
     */
    public function execute(
        array $attributes,
        string $temporaryPath,
        string $originalFilename,
        string $extension,
        int $clienteId,
        ?int $userId,
        DateTimeImmutable $now,
    ): Importacao {
        $storedName = $this->repository->storeUploadedFile(
            $temporaryPath,
            $originalFilename,
            $extension,
            'importacao',
        );

        return $this->repository->create([
            'cliente_id' => $clienteId,
            'tipo_importacao' => $attributes['tipo_importacao'],
            'arquivo_importado' => $storedName,
            'avisos' => 'Arquivo recebido. Processamento completo de carga (carga_*) deferido na Onda E.',
            'data_cadastro' => $now->format('Y-m-d H:i:s'),
            'usuario_criador_id' => $userId,
            'status' => 1,
        ]);
    }
}
