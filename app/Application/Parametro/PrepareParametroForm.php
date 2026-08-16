<?php

namespace App\Application\Parametro;

use App\Domain\Parametro\Parametro;
use App\Domain\Parametro\ParametroRepositoryInterface;

final class PrepareParametroForm
{
    public function __construct(
        private readonly ParametroRepositoryInterface $repository,
    ) {}

    /**
     * @return array{parametro: ?Parametro, tipoArr: array<string, string>}
     */
    public function execute(?int $id = null): array
    {
        $parametro = null;
        if ($id !== null) {
            $parametro = $this->repository->findById($id);
        }

        return [
            'parametro' => $parametro,
            'tipoArr' => $this->repository->distinctTipos(),
        ];
    }
}
