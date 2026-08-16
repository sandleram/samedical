<?php

namespace App\Application\MhCritico;

use App\Domain\MhCritico\MhCritico;
use App\Domain\MhCritico\MhCriticoRepositoryInterface;

final class PrepareMhCriticoForm
{
    public function __construct(
        private readonly MhCriticoRepositoryInterface $repository,
    ) {}

    /**
     * @return array{row: ?MhCritico, options: array<string, mixed>}
     */
    public function execute(?int $id = null): array
    {
        $row = null;
        if ($id !== null) {
            $row = $this->repository->findById($id);
        }

        return [
            'row' => $row,
            'options' => $this->repository->formOptions(),
        ];
    }
}
