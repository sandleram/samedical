<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MhCriticoHistorico extends Model
{
    protected $table = 'mh_critico_historico';

    public $timestamps = false;

    protected $fillable = [
        'mh_critico_id',
        'ciclo',
        'status_ciclo',
        'descricao',
        'data_cadastro',
        'usuario_id',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'mh_critico_id' => 'integer',
            'ciclo' => 'integer',
            'status_ciclo' => 'integer',
            'usuario_id' => 'integer',
            'status' => 'integer',
            'data_cadastro' => 'datetime',
        ];
    }

    public function critico(): BelongsTo
    {
        return $this->belongsTo(MhCritico::class, 'mh_critico_id');
    }
}
