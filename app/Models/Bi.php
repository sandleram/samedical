<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Bi extends Model
{
    protected $table = 'bi';

    public $timestamps = false;

    protected $fillable = [
        'grupo_empresarial_id',
        'cliente_id',
        'titulo',
        'subtitulo',
        'link',
        'observacao',
        'ordem',
        'data_cadastro',
        'usuario_id',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'grupo_empresarial_id' => 'integer',
            'cliente_id' => 'integer',
            'ordem' => 'integer',
            'usuario_id' => 'integer',
            'status' => 'integer',
            'data_cadastro' => 'datetime',
        ];
    }

    public function grupoEmpresarial(): BelongsTo
    {
        return $this->belongsTo(GrupoEmpresarial::class, 'grupo_empresarial_id');
    }

    public function cliente(): BelongsTo
    {
        return $this->belongsTo(Cliente::class, 'cliente_id');
    }

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }

    public function usuarios(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'usuario_bi', 'bi_id', 'usuario_id');
    }

    public function scopeForTenant(Builder $query): Builder
    {
        $grupoId = session('grupo_empresarial_id');
        $clienteId = session('cliente_id');

        if ($grupoId) {
            $query->where('grupo_empresarial_id', $grupoId);
        }

        if ($clienteId) {
            $query->where(function (Builder $q) use ($clienteId) {
                $q->whereNull('cliente_id')->orWhere('cliente_id', $clienteId);
            });
        }

        return $query;
    }
}
