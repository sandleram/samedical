<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MhNegociacao extends Model
{
    protected $table = 'mh_negociacao';

    public $timestamps = false;

    protected $fillable = [
        'mh_prestador_id',
        'tipo_negocio',
        'usuario_negociador_id',
        'usuario_id',
        'data_cadastro',
        'usuario_negociador_id_old',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'mh_prestador_id' => 'integer',
            'tipo_negocio' => 'integer',
            'usuario_negociador_id' => 'integer',
            'usuario_id' => 'integer',
            'usuario_negociador_id_old' => 'integer',
            'status' => 'integer',
            'data_cadastro' => 'datetime',
        ];
    }

    public function prestador(): BelongsTo
    {
        return $this->belongsTo(MhPrestador::class, 'mh_prestador_id');
    }
}
