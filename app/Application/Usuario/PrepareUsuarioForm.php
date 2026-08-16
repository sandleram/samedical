<?php

namespace App\Application\Usuario;

use App\Domain\Perfil\PerfilRepositoryInterface;
use App\Domain\Shared\TenantScope;
use App\Domain\Usuario\Usuario;
use App\Domain\Usuario\UsuarioRepositoryInterface;

final class PrepareUsuarioForm
{
    public function __construct(
        private readonly UsuarioRepositoryInterface $usuarios,
        private readonly PerfilRepositoryInterface $perfis,
    ) {}

    /**
     * @return array{
     *   usuario: ?Usuario,
     *   perfilArr: array<int|string, string>,
     *   selectClienteNew: array<int, list<array<string, mixed>>>,
     *   selectBi: array<int, list<array<string, mixed>>>,
     *   selectedClientes: list<int>,
     *   selectedBis: list<int>
     * }
     */
    public function execute(
        TenantScope $tenant,
        bool $isRoot,
        ?int $id = null,
    ): array {
        $usuario = null;
        if ($id !== null) {
            $usuario = $this->usuarios->findById($id, $tenant, $isRoot);
        }

        $perfilOptions = $this->perfis->optionsActive(includeRootPerfil: $isRoot);

        return [
            'usuario' => $usuario,
            'perfilArr' => ['' => 'Perfil...'] + $perfilOptions,
            'selectClienteNew' => $this->usuarios->clienteMatrix($tenant->grupoEmpresarialId, $isRoot),
            'selectBi' => $this->usuarios->biMatrix(),
            'selectedClientes' => $usuario?->clienteIds ?? [],
            'selectedBis' => $usuario?->biIds ?? [],
        ];
    }
}
