<?php

namespace App\Domain\Relatorio;

final class RelatorioAtendimentoPendenteRow
{
    public function __construct(
        public readonly ?int $id,
        public readonly ?\DateTimeImmutable $dataHora,
        public readonly ?int $usuarioId,
        public readonly ?int $usuarioAgendamentoId,
        public readonly mixed $status,
        public readonly ?string $usuarioNome,
        public readonly ?string $usuarioAgendamentoNome,
    ) {}

    public function __get(string $name): mixed
    {
        return match ($name) {
            'id' => $this->id,
            'data_hora' => $this->dataHora,
            'usuario_id' => $this->usuarioId,
            'usuario_agendamento_id' => $this->usuarioAgendamentoId,
            'status' => $this->status,
            'usuario' => (object) ['nome' => $this->usuarioNome],
            'usuarioAgendamento' => (object) ['nome' => $this->usuarioAgendamentoNome],
            default => null,
        };
    }

    public function __isset(string $name): bool
    {
        return $this->__get($name) !== null;
    }
}
