<?php

namespace App\Domain\Relatorio;

final class RelatorioAtendimentoPendenteSearchCriteria
{
    public function __construct(
        public readonly string $cod = '',
        public readonly string $usuarioAgendamentoId = '',
        public readonly string $status = '',
        public readonly int $perfilId = 0,
        public readonly ?int $usuarioId = null,
        public readonly int $perPage = 30,
        public readonly int $page = 1,
    ) {}
}
