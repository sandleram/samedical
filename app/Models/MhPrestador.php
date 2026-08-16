<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MhPrestador extends Model
{
    protected $table = 'mh_prestador';

    public $timestamps = false;

    protected $fillable = [
        'id_hubspot',
        'nome',
        'cidade',
        'estado',
        'praca',
        'atividade',
        'descricao',
        'data_cadastro',
        'usuario_id',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'usuario_id' => 'integer',
            'status' => 'integer',
            'data_cadastro' => 'datetime',
        ];
    }

    public function criticos(): HasMany
    {
        return $this->hasMany(MhCritico::class, 'mh_prestador_id');
    }

    public function negociacoes(): HasMany
    {
        return $this->hasMany(MhNegociacao::class, 'mh_prestador_id');
    }
}
