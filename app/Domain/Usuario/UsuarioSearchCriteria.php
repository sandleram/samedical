<?php

namespace App\Domain\Usuario;

final class UsuarioSearchCriteria
{
    public function __construct(
        public readonly string $id = '',
        public readonly string $nome = '',
        public readonly string $usuario = '',
        public readonly string $email = '',
        public readonly string $perfil = '',
        public readonly string $status = '',
        public readonly bool $excludeRootUser = false,
        public readonly int $perPage = 10,
        public readonly int $page = 1,
    ) {}
}
