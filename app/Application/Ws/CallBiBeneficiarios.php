<?php

namespace App\Application\Ws;

use App\Domain\Integration\IntegrationTokenSettingsInterface;
use App\Domain\Integration\TokenMatcher;
use App\Domain\Ws\WsBiRepositoryInterface;
use App\Domain\Ws\WsCallResult;

final class CallBiBeneficiarios
{
    public function __construct(
        private readonly IntegrationTokenSettingsInterface $tokens,
        private readonly WsBiRepositoryInterface $repository,
    ) {}

    public function execute(string $token, ?int $clienteId, int $limit, string $endpoint = 'call_bi_beneficiarios'): WsCallResult
    {
        if (! TokenMatcher::matches($this->tokens->wsToken(), $token)) {
            return WsCallResult::forbidden();
        }

        $safeLimit = min(100, max(1, $limit));
        $data = $this->repository->listBeneficiarios($clienteId, $safeLimit);

        return WsCallResult::success($endpoint, $data);
    }
}
