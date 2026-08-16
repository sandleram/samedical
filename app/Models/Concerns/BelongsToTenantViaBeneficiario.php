<?php

namespace App\Models\Concerns;

use Illuminate\Database\Eloquent\Builder;

trait BelongsToTenantViaBeneficiario
{
    public function scopeForTenant(Builder $query): Builder
    {
        $grupoId = session('grupo_empresarial_id');
        $clienteId = session('cliente_id');

        if ($clienteId) {
            $query->whereHas('beneficiario', function (Builder $q) use ($clienteId) {
                $q->where('cliente_id', $clienteId);
            });
        } elseif ($grupoId) {
            $query->whereHas('beneficiario.cliente', function (Builder $q) use ($grupoId) {
                $q->where('grupo_empresarial_id', $grupoId);
            });
        }

        return $query;
    }
}
