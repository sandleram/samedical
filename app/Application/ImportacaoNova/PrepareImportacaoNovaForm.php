<?php

namespace App\Application\ImportacaoNova;

use App\Domain\ImportacaoNova\ImportacaoNovaRepositoryInterface;

final class PrepareImportacaoNovaForm
{
    public function __construct(
        private readonly ImportacaoNovaRepositoryInterface $repository,
    ) {}

    /**
     * @return array{tipoImportacaoArr: array<string, string>, statusProcessoArr: array<int, string>}
     */
    public function execute(bool $tipoWithPlaceholder = true): array
    {
        $tipos = $this->repository->tipoImportacaoOptions();
        if ($tipoWithPlaceholder) {
            $tipos = ['' => 'Selecione...'] + $tipos;
        }

        return [
            'tipoImportacaoArr' => $tipos,
            'statusProcessoArr' => $this->repository->statusProcessoOptions(),
        ];
    }
}
