<?php

namespace App\Application\Perfil;

use App\Domain\Perfil\PerfilRepositoryInterface;

final class GetPerfilSelectOptions
{
    public function __construct(
        private readonly PerfilRepositoryInterface $perfis,
    ) {}

    /**
     * @return array<int|string, string>
     */
    public function execute(bool $isRoot): array
    {
        return ['' => 'Perfil...'] + $this->perfis->optionsActive(includeRootPerfil: $isRoot);
    }
}
