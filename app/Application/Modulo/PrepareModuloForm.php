<?php

namespace App\Application\Modulo;

use App\Domain\Modulo\Modulo;
use App\Domain\Modulo\ModuloRepositoryInterface;

final class PrepareModuloForm
{
    public function __construct(
        private readonly ModuloRepositoryInterface $modulos,
    ) {}

    /**
     * @return array{modulo: ?Modulo, moduloArr: array<int|string, string>}
     */
    public function execute(?int $id = null): array
    {
        $modulo = null;
        if ($id !== null) {
            $modulo = $this->modulos->findById($id);
        }

        return [
            'modulo' => $modulo,
            'moduloArr' => $this->modulos->parentOptions(),
        ];
    }
}
