<?php

namespace App\Domain\Agendamento;

/**
 * Entidade de domínio. Sem dependências Laravel.
 */
final class Agendamento
{
    public function __construct(
        public readonly ?int $id,
        public readonly ?int $atendimentoId,
        public readonly ?int $usuarioId,
        public readonly ?int $usuarioAgendamentoId,
        public readonly ?\DateTimeImmutable $dataHora,
        public readonly ?string $descricao,
        public readonly int $status,
        public readonly ?string $beneficiarioNome = null,
        public readonly ?string $usuarioNome = null,
        public readonly ?string $usuarioAgendamentoNome = null,
    ) {}

    public function __get(string $name): mixed
    {
        return match ($name) {
            'id' => $this->id,
            'atendimento_id' => $this->atendimentoId,
            'usuario_id' => $this->usuarioId,
            'usuario_agendamento_id' => $this->usuarioAgendamentoId,
            'data_hora' => $this->dataHora,
            'descricao' => $this->descricao,
            'status' => $this->status,
            'atendimento' => (object) ['id' => $this->atendimentoId, 'beneficiario' => (object) ['nome' => $this->beneficiarioNome]],
            'usuario' => (object) ['nome' => $this->usuarioNome, 'id' => $this->usuarioId],
            'usuarioAgendamento' => (object) ['nome' => $this->usuarioAgendamentoNome, 'id' => $this->usuarioAgendamentoId],
            default => null,
        };
    }

    public function __isset(string $name): bool
    {
        return $this->__get($name) !== null;
    }
}
