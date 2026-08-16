<?php

namespace App\Application\Importacao;

use App\Domain\Importacao\ImportacaoRepositoryInterface;

final class PrepareImportacaoForm
{
    public function __construct(
        private readonly ImportacaoRepositoryInterface $repository,
    ) {}

    /**
     * @return array{tipoImportacaoArr: array<string, string>}
     */
    public function execute(bool $withPlaceholder = true): array
    {
        $tipos = $this->repository->tipoImportacaoOptions();
        if ($withPlaceholder) {
            $tipos = ['' => 'Selecione...'] + $tipos;
        }

        return ['tipoImportacaoArr' => $tipos];
    }
}
