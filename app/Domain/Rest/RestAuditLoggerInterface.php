<?php

namespace App\Domain\Rest;

interface RestAuditLoggerInterface
{
    public function write(string $log, string $description, string $serverDescription, int $usuarioId = 1): void;
}
