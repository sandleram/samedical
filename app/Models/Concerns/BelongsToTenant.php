<?php

namespace App\Models\Concerns;

use Illuminate\Database\Eloquent\Builder;

trait BelongsToTenant
{
    public function scopeForTenant(Builder $query): Builder
    {
        $grupoId = session('grupo_empresarial_id');
        $clienteId = session('cliente_id');

        if ($grupoId) {
            $query->where($this->getTable().'.grupo_empresarial_id', $grupoId);
        }

        if ($clienteId) {
            $query->where($this->getTable().'.cliente_id', $clienteId);
        }

        return $query;
    }
}
