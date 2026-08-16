<?php

namespace App\Application\Db;

use App\Domain\Db\DbSettingsInterface;

final class GetDbIndex
{
    public function __construct(
        private readonly DbSettingsInterface $settings,
    ) {}

    /**
     * @return array{title: string, phpmyadminUrl: string}
     */
    public function execute(): array
    {
        return [
            'title' => 'DB',
            'phpmyadminUrl' => $this->settings->phpMyAdminUrl(),
        ];
    }
}
