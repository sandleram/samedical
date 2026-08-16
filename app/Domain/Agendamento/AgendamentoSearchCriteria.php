<?php

namespace App\Domain\Agendamento;

final class AgendamentoSearchCriteria
{
    public function __construct(
        public readonly string $cod = '',
        public readonly string $usuarioAgendamentoId = '',
        public readonly string $status = '',
        public readonly ?int $restrictToUsuarioId = null,
        public readonly bool $onlyOpen = true,
        public readonly bool $onlyActiveForNonRoot = false,
        public readonly int $perPage = 15,
        public readonly int $page = 1,
    ) {}
}
