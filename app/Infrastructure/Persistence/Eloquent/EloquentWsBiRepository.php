<?php

namespace App\Infrastructure\Persistence\Eloquent;

use App\Domain\Ws\WsBiRepositoryInterface;
use App\Models\DwBeneficiario;

final class EloquentWsBiRepository implements WsBiRepositoryInterface
{
    public function listBeneficiarios(?int $clienteId, int $limit): array
    {
        $query = DwBeneficiario::query()->orderByDesc('id')->limit($limit);

        if ($clienteId !== null) {
            $query->where('cliente_id', $clienteId);
        }

        return $query->get([
            'id', 'cliente_id', 'nome', 'cpf', 'competencia', 'operadora', 'chave_beneficiario',
        ])->map(fn ($row) => $row->toArray())->values()->all();
    }
}
