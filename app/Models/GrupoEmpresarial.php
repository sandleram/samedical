<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class GrupoEmpresarial extends Model
{
    protected $table = 'grupo_empresarial';

    public $timestamps = false;

    protected $fillable = [
        'nome',
        'img_logo',
        'bi',
        'cor',
        'data_cadastro',
        'status',
        'data_cancelamento',
    ];

    protected function casts(): array
    {
        return [
            'status' => 'integer',
            'data_cadastro' => 'datetime',
            'data_cancelamento' => 'datetime',
        ];
    }

    public function clientes(): HasMany
    {
        return $this->hasMany(Cliente::class, 'grupo_empresarial_id');
    }

    /**
     * Root vê todos; demais filtram pelo GE da sessão (quando definido).
     */
    public function scopeForTenant(Builder $query): Builder
    {
        $user = auth()->user();
        if ($user?->isRoot()) {
            return $query;
        }

        $grupoId = session('grupo_empresarial_id');
        if ($grupoId) {
            $query->where($this->getTable().'.id', (int) $grupoId);
        }

        return $query;
    }
}
