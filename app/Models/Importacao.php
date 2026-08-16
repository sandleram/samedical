<?php

namespace App\Models;

use App\Models\Concerns\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Importacao extends Model
{
    use BelongsToTenant;

    protected $table = 'importacao';

    public $timestamps = false;

    protected $fillable = [
        'cliente_id',
        'tipo_importacao',
        'arquivo_importado',
        'avisos',
        'data_cadastro',
        'usuario_criador_id',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'cliente_id' => 'integer',
            'usuario_criador_id' => 'integer',
            'data_cadastro' => 'datetime',
            'status' => 'integer',
        ];
    }

    public function cliente(): BelongsTo
    {
        return $this->belongsTo(Cliente::class, 'cliente_id');
    }

    public function usuarioCriador(): BelongsTo
    {
        return $this->belongsTo(User::class, 'usuario_criador_id');
    }
}
