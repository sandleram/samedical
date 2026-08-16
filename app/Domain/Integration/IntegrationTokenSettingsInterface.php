<?php

namespace App\Domain\Integration;

interface IntegrationTokenSettingsInterface
{
    public function restToken(): string;

    public function wsToken(): string;
}
