<?php

namespace App\Application\ImportacaoNova;

use App\Domain\ImportacaoNova\ImportacaoNova;
use App\Domain\ImportacaoNova\ImportacaoNovaRepositoryInterface;
use DateTimeImmutable;

final class SaveImportacaoNova
{
    public function __construct(
        private readonly ImportacaoNovaRepositoryInterface $repository,
    ) {}

    /**
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
    ): ImportacaoNova {
        $storedName = $this->repository->storeUploadedFile(
            $temporaryPath,
            $originalFilename,
            $extension,
            'importacao_nova/aguardando',
        );

        return $this->repository->create([
            'cliente_id' => $clienteId,
            'nome_arquivo' => $originalFilename,
            'tipo_importacao' => $attributes['tipo_importacao'],
            'arquivo_importado' => $storedName,
            'linhas_totais' => '0',
            'linhas_processadas' => '0',
            'data_cadastro' => $now->format('Y-m-d H:i:s'),
            'usuario_criador_id' => $userId,
            'status_processo' => 0,
            'status' => 1,
        ]);
    }
}
