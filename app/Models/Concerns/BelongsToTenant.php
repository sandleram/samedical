<?php

namespace App\Models\Concerns;

use Illuminate\Database\Eloquent\Builder;

trait BelongsToTenant
{
    public function scopeForTenant(Builder $query): Builder
    {
        $grupoId = session('grupo_empresarial_id');
        $clienteId = session('cliente_id');

        if ($clienteId) {
            $query->where($this->getTable().'.cliente_id', $clienteId);
        } elseif ($grupoId) {
            $query->whereHas('cliente', function (Builder $clienteQuery) use ($grupoId) {
                $clienteQuery->where('grupo_empresarial_id', $grupoId);
            });
        }

        return $query;
    }
}
