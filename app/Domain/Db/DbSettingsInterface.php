<?php

namespace App\Domain\Db;

/**
 * Configuração do utilitário DB (sem secrets — só URL externa).
 */
interface DbSettingsInterface
{
    public function phpMyAdminUrl(): string;
}
