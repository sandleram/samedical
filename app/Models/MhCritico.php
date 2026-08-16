<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MhCritico extends Model
{
    protected $table = 'mh_critico';

    public $timestamps = false;

    protected $fillable = [
        'mh_prestador_id',
        'mh_prestador_principal_id',
        'principal',
        'nome',
        'opcao',
        'ciclo',
        'status_ciclo',
        'data_cadastro',
        'data_atualizacao',
        'usuario_id',
        'usuario_atualizacao_id',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'mh_prestador_id' => 'integer',
            'mh_prestador_principal_id' => 'integer',
            'principal' => 'integer',
            'opcao' => 'integer',
            'ciclo' => 'integer',
            'status_ciclo' => 'integer',
            'status' => 'integer',
            'data_cadastro' => 'datetime',
            'data_atualizacao' => 'datetime',
        ];
    }

    public function prestador(): BelongsTo
    {
        return $this->belongsTo(MhPrestador::class, 'mh_prestador_id');
    }

    public function prestadorPrincipal(): BelongsTo
    {
        return $this->belongsTo(MhPrestador::class, 'mh_prestador_principal_id');
    }

    public function historicos(): HasMany
    {
        return $this->hasMany(MhCriticoHistorico::class, 'mh_critico_id');
    }
}
