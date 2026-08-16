<?php

namespace App\Application\Perfil;

use App\Domain\Modulo\ModuloRepositoryInterface;
use App\Domain\Perfil\Perfil;
use App\Domain\Perfil\PerfilRepositoryInterface;

final class PreparePerfilForm
{
    public function __construct(
        private readonly PerfilRepositoryInterface $perfis,
        private readonly ModuloRepositoryInterface $modulos,
    ) {}

    /**
     * @return array{
     *   perfil: ?Perfil,
     *   modulos: list<\App\Domain\Modulo\Modulo>,
     *   permissoesSalvas: array<int, array{id: int|string, permissao: int}>
     * }
     */
    public function execute(?int $id = null): array
    {
        $perfil = null;
        $permissoesSalvas = [];

        if ($id !== null) {
            $perfil = $this->perfis->findById($id);
            if ($perfil) {
                foreach ($perfil->perfilModulos as $pm) {
                    $permissoesSalvas[(int) $pm->modulo_id] = [
                        'id' => $pm->id ?? '',
                        'permissao' => (int) $pm->permissao,
                    ];
                }
            }
        }

        return [
            'perfil' => $perfil,
            'modulos' => $this->modulos->listActiveOrdered(),
            'permissoesSalvas' => $permissoesSalvas,
        ];
    }
}
