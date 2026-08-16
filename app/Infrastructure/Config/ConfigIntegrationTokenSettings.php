<?php

namespace App\Infrastructure\Config;

use App\Domain\Integration\IntegrationTokenSettingsInterface;

final class ConfigIntegrationTokenSettings implements IntegrationTokenSettingsInterface
{
    public function restToken(): string
    {
        return (string) config('samed.rest.token', '');
    }

    public function wsToken(): string
    {
        $ws = (string) config('samed.ws.token', '');

        return $ws !== '' ? $ws : $this->restToken();
    }
}
