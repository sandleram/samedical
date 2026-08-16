<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Subfatura extends Model
{
    protected $table = 'subfatura';

    public $timestamps = false;

    protected $fillable = [
        'beneficio_id',
        'descricao',
        'codigo',
        'data_cadastro',
        'status',
        'data_cancelamento',
    ];

    protected function casts(): array
    {
        return [
            'beneficio_id' => 'integer',
            'status' => 'integer',
            'data_cadastro' => 'datetime',
            'data_cancelamento' => 'date',
        ];
    }

    public function beneficio(): BelongsTo
    {
        return $this->belongsTo(Beneficio::class, 'beneficio_id');
    }

    public function scopeForTenant(Builder $query): Builder
    {
        $grupoId = session('grupo_empresarial_id');
        $clienteId = session('cliente_id');

        if ($clienteId) {
            $query->whereHas('beneficio', function (Builder $q) use ($clienteId) {
                $q->where('cliente_id', $clienteId);
            });
        } elseif ($grupoId) {
            $query->whereHas('beneficio.cliente', function (Builder $q) use ($grupoId) {
                $q->where('grupo_empresarial_id', $grupoId);
            });
        }

        return $query;
    }
}
