<?php

namespace App\Infrastructure\Persistence\Eloquent;

use App\Domain\Rest\RestAuditLoggerInterface;
use App\Models\AuditLog;

final class EloquentRestAuditLogger implements RestAuditLoggerInterface
{
    public function write(string $log, string $description, string $serverDescription, int $usuarioId = 1): void
    {
        try {
            AuditLog::query()->create([
                'log' => $log,
                'mensagem' => '',
                'description' => $description,
                'server_description' => $serverDescription,
                'data_cadastro' => now(),
                'usuario_id' => $usuarioId,
            ]);
        } catch (\Throwable) {
            // Não bloquear a API se a tabela log estiver indisponível.
        }
    }
}
