<?php

namespace App\Application\Rest;

use App\Domain\Integration\IntegrationTokenSettingsInterface;
use App\Domain\Integration\TokenMatcher;
use App\Domain\Rest\RestApiResult;
use App\Domain\Rest\RestAuditLoggerInterface;
use App\Domain\Rest\RestProativaRepositoryInterface;

final class GetRestProativaSinistros
{
    public function __construct(
        private readonly IntegrationTokenSettingsInterface $tokens,
        private readonly RestProativaRepositoryInterface $repository,
        private readonly RestAuditLoggerInterface $audit,
    ) {}

    public function execute(string $token, ?int $clienteId, string $ip): RestApiResult
    {
        $description = 'bi_proativa_sinistros';

        if (! TokenMatcher::matches($this->tokens->restToken(), $token) || $clienteId === null) {
            $this->audit->write('Erro - Rest API - Proativa Sinistros', $description, 'IP: '.$ip);

            return RestApiResult::failed();
        }

        $rows = $this->repository->sinistrosByCliente($clienteId);
        if ($rows === []) {
            $this->audit->write('Erro - Rest API - Proativa Sinistros', $description, 'IP: '.$ip);

            return RestApiResult::failed();
        }

        $this->audit->write('Rest API - Proativa Sinistros', $description, 'IP: '.$ip);

        return RestApiResult::success(['sinistro' => $rows]);
    }
}
