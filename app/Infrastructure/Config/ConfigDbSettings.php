<?php

namespace App\Infrastructure\Config;

use App\Domain\Db\DbSettingsInterface;

final class ConfigDbSettings implements DbSettingsInterface
{
    public function phpMyAdminUrl(): string
    {
        return (string) config('samed.db.phpmyadmin_url', '');
    }
}
