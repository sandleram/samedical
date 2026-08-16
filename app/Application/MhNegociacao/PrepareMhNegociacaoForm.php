<?php

namespace App\Application\MhNegociacao;

use App\Domain\MhNegociacao\MhNegociacao;
use App\Domain\MhNegociacao\MhNegociacaoRepositoryInterface;

final class PrepareMhNegociacaoForm
{
    public function __construct(
        private readonly MhNegociacaoRepositoryInterface $repository,
    ) {}

    /**
     * @return array{row: ?MhNegociacao, options: array<string, mixed>}
     */
    public function execute(?int $id = null): array
    {
        $row = null;
        if ($id !== null) {
            $row = $this->repository->findById($id);
        }

        return [
            'row' => $row,
            'options' => [
                'listPrestador' => $this->repository->formPrestadorOptions(),
            ],
        ];
    }
}
