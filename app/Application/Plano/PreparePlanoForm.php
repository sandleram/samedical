<?php

namespace App\Application\Plano;

use App\Domain\Operadora\OperadoraRepositoryInterface;
use App\Domain\Plano\Plano;
use App\Domain\Plano\PlanoRepositoryInterface;

final class PreparePlanoForm
{
    public function __construct(
        private readonly PlanoRepositoryInterface $planos,
        private readonly OperadoraRepositoryInterface $operadoras,
    ) {}

    /**
     * @return array{
     *     plano: ?Plano,
     *     operadoraArr: array<int|string, string>,
     *     tipoBeneficioArr: array<int|string, string>
     * }
     */
    public function execute(?int $id, bool $withPlaceholder): array
    {
        $plano = null;
        if ($id !== null) {
            $plano = $this->planos->findById($id);
        }

        return [
            'plano' => $plano,
            'operadoraArr' => $this->operadoras->options($withPlaceholder),
            'tipoBeneficioArr' => $this->planos->tipoBeneficioOptions($withPlaceholder),
        ];
    }
}
