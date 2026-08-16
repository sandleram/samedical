<?php

namespace App\Domain\LogEntry;

/**
 * Entidade de domínio para tabela legado `log`.
 * Namespace LogEntry evita conflito com Illuminate\Support\Facades\Log.
 */
final class LogEntry
{
    public function __construct(
        public readonly ?int $id,
        public readonly ?string $log,
        public readonly ?string $mensagem,
        public readonly ?string $description,
        public readonly ?string $serverDescription,
        public readonly ?\DateTimeImmutable $dataCadastro,
        public readonly ?int $usuarioId,
        public readonly ?string $usuarioNome = null,
    ) {}

    public function __get(string $name): mixed
    {
        return match ($name) {
            'id' => $this->id,
            'log' => $this->log,
            'mensagem' => $this->mensagem,
            'description' => $this->description,
            'server_description' => $this->serverDescription,
            'data_cadastro' => $this->dataCadastro,
            'usuario_id' => $this->usuarioId,
            'usuario' => (object) ['nome' => $this->usuarioNome],
            default => null,
        };
    }

    public function __isset(string $name): bool
    {
        return $this->__get($name) !== null;
    }
}
