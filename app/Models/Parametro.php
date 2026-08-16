<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Parametro extends Model
{
    protected $table = 'parametro';

    public $timestamps = false;

    protected $fillable = [
        'nome',
        'valor',
        'ordenacao',
        'tipo',
        'data_atualizacao',
        'data_cadastro',
        'usuario_id',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'ordenacao' => 'integer',
            'usuario_id' => 'integer',
            'status' => 'integer',
            'data_cadastro' => 'datetime',
            'data_atualizacao' => 'datetime',
        ];
    }

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }
}
