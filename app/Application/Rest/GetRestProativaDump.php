<?php

namespace App\Application\Rest;

use App\Domain\Integration\IntegrationTokenSettingsInterface;
use App\Domain\Integration\TokenMatcher;
use App\Domain\Rest\RestApiResult;
use App\Domain\Rest\RestAuditLoggerInterface;
use App\Domain\Rest\RestProativaRepositoryInterface;

/**
 * Dumps Proativa sem filtro cliente_id na query (legado).
 */
final class GetRestProativaDump
{
    private const LABELS = [
        'beneficio' => 'Beneficio',
        'cliente' => 'Cliente',
        'grupo_estatistico' => 'GrupoEstatistico',
        'cronicos' => 'Cronicos',
        'subfaturas' => 'Subfaturas',
        'procedimento' => 'Procedimento',
    ];

    public function __construct(
        private readonly IntegrationTokenSettingsInterface $tokens,
        private readonly RestProativaRepositoryInterface $repository,
        private readonly RestAuditLoggerInterface $audit,
    ) {}

    public function execute(string $resource, string $token, string $ip): RestApiResult
    {
        $label = self::LABELS[$resource] ?? null;
        if ($label === null) {
            return RestApiResult::failed();
        }

        $description = 'bi_proativa_'.$resource;

        if (! TokenMatcher::matches($this->tokens->restToken(), $token)) {
            $this->audit->write('Erro - Rest API - Proativa '.$label, $description, 'IP: '.$ip);

            return RestApiResult::failed();
        }

        $rows = $this->repository->dump($resource);
        if ($rows === []) {
            $this->audit->write('Erro - Rest API - Proativa '.$label, $description, 'IP: '.$ip);

            return RestApiResult::failed();
        }

        $this->audit->write('Rest API - Proativa '.$label, $description, 'IP: '.$ip);

        return RestApiResult::success([$resource => $rows]);
    }
}
